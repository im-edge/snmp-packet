IMEdge\\SnmpPacket
==================

SNMP packet parser and renderer (encoder/decoder).


[![Coding Standards](https://github.com/im-edge/snmp-packet/actions/workflows/CodingStandards.yml/badge.svg)](https://github.com/im-edge/snmp-packet/actions/workflows/CodingStandards.yml)
[![Unit Tests](https://github.com/im-edge/snmp-packet/actions/workflows/UnitTests.yml/badge.svg)](https://github.com/im-edge/snmp-packet/actions/workflows/UnitTests.yml)
[![Static Analysis](https://github.com/im-edge/snmp-packet/actions/workflows/StaticAnalysis.yml/badge.svg)](https://github.com/im-edge/snmp-packet/actions/workflows/StaticAnalysis.yml)
[![PHPStan Level 9](https://img.shields.io/badge/PHPStan-level%209-brightgreen.svg?style=flat)](https://phpstan.org/)
[![Minimum PHP Version: 8.1](https://img.shields.io/badge/php-%3E%3D%208.1-8892BF.svg)](https://php.net/)
[![License: MIT](https://poser.pugx.org/imedge/snmp-packet/license)](https://choosealicense.com/licenses/mit/)
[![Version](https://poser.pugx.org/imedge/snmp-packet/version)](https://packagist.org/packages/imedge/snmp-packet)

Implemented Standards and vendor-specific extensions
----------------------------------------------------

There are quite some RFCs and Drafts involved, I'm trying to give some overview.

## SNMPv1

While being very old, SNMPv1 is still relevant, and therefore supported.

### A Simple Network Management Protocol (SNMP)

SNMPv1, the initial implementation of the SNMP protocol, has been defined in the 1980s.
However, it is still relevant, and therefore supported:

## SNMPv2c

TODO

### SNMPv3

We implemented and tested all official and (well-known) vendor-specific authentication
and privacy (encryption) methods.

#### Authentication Methods

The User-based Security Model (USM) hast been implemented according to
[RFC 3414](https://datatracker.ietf.org/doc/html/rfc3414) (User-based Security Model (USM) for
version 3 of the Simple Network Management Protocol (SNMPv3)). All authentication mechanisms from
that RFC and from [RFC7860](https://datatracker.ietf.org/doc/html/rfc7860) (HMAC-SHA-2 Authentication
Protocols in User-Based Security Model (USM) for SNMPv3) have been implemented.

* MD5: required in [RFC 3414](https://datatracker.ietf.org/doc/html/rfc3414)
* SHA (SHA1): optional in [RFC 3414](https://datatracker.ietf.org/doc/html/rfc3414)
* SHA-224: optional in [RFC 7860](https://datatracker.ietf.org/doc/html/rfc7860) with 128bit HMAC
* SHA-256: required in [RFC 7860](https://datatracker.ietf.org/doc/html/rfc7860) with 192bit HMAC
* SHA-384: optional in [RFC 7860](https://datatracker.ietf.org/doc/html/rfc7860) with 256bit HMAC
* SHA-512: suggested in [RFC 7860](https://datatracker.ietf.org/doc/html/rfc7860) with 384bit HMAC

#### Privacy / Encryption Methods

* DES: in CBC mode, defined in [RFC 3414](https://datatracker.ietf.org/doc/html/rfc3414)
* 3-DES: Triple-DES EDE in "Outside" CBC mode, mostly used by Cisco, defined in [draft-reeder-snmpv3-usm-3desede-00](https://datatracker.ietf.org/doc/html/draft-reeder-snmpv3-usm-3desede-00)
* AES-128: in CFB mode, required in [RFC 3826](https://datatracker.ietf.org/doc/html/rfc3826) with "Blumenthal" Key localization
* AES-192: in CFB mode, as of [draft-blumenthal-aes-usm-04](https://datatracker.ietf.org/doc/html/draft-blumenthal-aes-usm-04) with "Blumenthal" Key localization
* AES-192: in CFB mode, "Cisco variant", therefore named AES-192-C, as of [draft-reeder-snmpv3-usm-3desede-00](https://datatracker.ietf.org/doc/html/draft-reeder-snmpv3-usm-3desede-00) with "Reeder" Key localization
* AES-256: in CFB mode, as of [draft-blumenthal-aes-usm-04](https://datatracker.ietf.org/doc/html/draft-blumenthal-aes-usm-04) with "Blumenthal" Key localization
* AES-256: in CFB mode, "Cisco variant", therefore named AES-256-C, as of [draft-reeder-snmpv3-usm-3desede-00](https://datatracker.ietf.org/doc/html/draft-reeder-snmpv3-usm-3desede-00) with "Reeder" Key localization

#### Crypto implementation

We're using system-provided MD5/SHA hashing methods, and encryption as provided by OpenSSL.
Everything related to (pre-IV), Key generation and Salt exchange have been implemented from
scratch.

#### SNMPv3 standardization history

January 1998 two sets of related RFCs have been published in parallel:

* RFC 2261–2270 (SNMPv3 "Standard")
* RFC 2271–2275 (SNMPv3 "Experimental")

The reason for this have been two irreconcilable factions in the IETF by the end of 1997:

* **SNMPv3 Working Group** (RFC 2261–2270): official approach, based on SNMPv2u (User-based Security).
  Main goal: faster standardization with minimal changes to SNMPv2
* **Secure SNMPv3 Ad-Hoc Group** (RFC 2271–2275): alternative approach, USM (User-based Security Model), focussed on
  higher security. This "rebel" draft has been pushed by Cisco, IBM and others, who considered the official draft
  too weak.

The "experimental" party eventually won, the official "Standard" suffered a silent death, has never been widely adopted,
and has only historic relevance es of today. In 1999 the experimental RFCs have been updated (RFC 2571–2575), became a
Draft Standard, and replaced all the former RFCs from both parties. Finally, in Dezember 2002, **RFC 3410-3418** became
an Internet Standard.

### Other related RFCs

TODO
