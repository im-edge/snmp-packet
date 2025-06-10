<?php

namespace IMEdge\SnmpPacket\Usm;

use IMEdge\SnmpPacket\Error\SnmpAuthenticationException;
use IMEdge\SnmpPacket\Message\SnmpV3Message;
use IMEdge\Tests\Snmp\TestHelper;

use function hash_hmac;
use function strlen;
use function substr;

class AuthenticationModule
{
    protected string $key;
    protected string $truncatedKey;

    /**
     * @throws SnmpAuthenticationException
     */
    public function __construct(
        #[\SensitiveParameter()]
        protected string $password,
        protected string $engineId,
        protected SnmpAuthProtocol $authProtocol,
    ) {
        self::assertValidPassword($password);
        $this->key = AuthKey::generate($authProtocol, $password, $this->engineId);
        $this->truncatedKey = substr($this->key, 0, $authProtocol->getTruncateSecretKeyLength());
    }

    /**
     * @throws SnmpAuthenticationException
     */
    public function authenticateOutgoingMsg(SnmpV3Message $message): SnmpV3Message
    {
        if (! $message->header->securityFlags->wantsAuthentication()) {
            return $message;
        }
        $usm = self::requireUsm($message);
        # RFC 7860, Section 4.2.1. Step 1:
        #     The msgAuthenticationParameters field is set to the serialization
        #     of an OCTET STRING containing N zero octets; it is serialized
        #     according to the rules in [RFC3417].
        $usm->authenticationParams = AuthenticationParams::getPlaceHolder($this->authProtocol);
        # RFC 7860, Section 4.2.1. Step 4:
        #     The msgAuthenticationParameters field is replaced with the MAC
        #     obtained in the previous step.
        $usm->authenticationParams = $this->generateHMAC($message);

        return $message;
    }

    public function authenticateIncomingMessage(SnmpV3Message $message): bool
    {
        $usm = self::requireUsm($message);
        $authenticationParams = $usm->authenticationParams;
        $usm->authenticationParams = AuthenticationParams::getPlaceHolder($this->authProtocol);
        $expected = $this->generateHMAC($message);
        $usm->authenticationParams = $authenticationParams;

        return $expected === $authenticationParams;
    }

    /**
     * @throws SnmpAuthenticationException
     */
    protected function generateHMAC(SnmpV3Message $message): string
    {
        # RFC 7860, Section 4.2.1. Step 2:
        #     Using the secret authKey of M octets, the HMAC is calculated over
        #     the wholeMsg according to RFC 6234 with hash function H.
        $hmac = hash_hmac($this->authProtocol->getHashAlgorithm(), $message->toBinary(), $this->truncatedKey, true);
        $this->throwOnHashError($hmac);

        # RFC 7860, Section 4.2.1. Step 3:
        #     The N first octets of the above HMAC are taken as the computed
        #     MAC value.
        return substr($hmac, 0, $this->authProtocol->getTruncateOutputLength());
    }

    /**
     * @throws SnmpAuthenticationException
     */
    protected function throwOnHashError(mixed $result): void
    {
        if ($result === false) {
            throw new SnmpAuthenticationException(\sprintf(
                "Unable to hash value using with %s algorithm",
                $this->authProtocol->getHashAlgorithm()
            ));
        }
    }

    /**
     * @throws SnmpAuthenticationException
     */
    protected static function requireUsm(SnmpV3Message $message): UserBasedSecurityModel
    {
        $usm = $message->securityParameters;
        if (! $usm instanceof UserBasedSecurityModel) {
            throw new SnmpAuthenticationException('USM is required');
        }

        return $usm;
    }

    /**
     * @throws SnmpAuthenticationException
     */
    protected static function assertValidPassword(string $password): void
    {
        # RFC 3414, Section 11.2. Passwords must be at least 8 characters in length
        if (strlen($password) < 8) {
            throw new SnmpAuthenticationException('The authentication password must be at least 8 characters long.');
        }
    }
}
