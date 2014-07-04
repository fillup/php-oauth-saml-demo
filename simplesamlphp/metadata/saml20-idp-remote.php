<?php
/**
 * SAML 2.0 remote IdP metadata for simpleSAMLphp.
 *
 * Remember to remove the IdPs you don't use from this file.
 *
 * See: https://rnd.feide.no/content/idp-remote-metadata-reference
 */

/*
 * Guest IdP. allows users to sign up and register. Great for testing!
 */
//$metadata['https://openidp.feide.no'] = array(
//	'name' => array(
//		'en' => 'Feide OpenIdP - guest users',
//		'no' => 'Feide Gjestebrukere',
//	),
//	'description'          => 'Here you can login with your account on Feide RnD OpenID. If you do not already have an account on this identity provider, you can create a new one by following the create new account link and follow the instructions.',
//
//	'SingleSignOnService'  => 'https://openidp.feide.no/simplesaml/saml2/idp/SSOService.php',
//	'SingleLogoutService'  => 'https://openidp.feide.no/simplesaml/saml2/idp/SingleLogoutService.php',
//	'certFingerprint'      => 'c9ed4dfb07caf13fc21e0fec1572047eb8a7a4cb'
//);

$metadata['http://saml.local/saml2/idp/metadata.php'] = array (
    'entityid' => 'http://saml.local/saml2/idp/metadata.php',
    'contacts' =>
        array (
        ),
    'metadata-set' => 'saml20-idp-remote',
    'SingleSignOnService' =>
        array (
            0 =>
                array (
                    'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
                    'Location' => 'http://saml.local/saml2/idp/SSOService.php',
                ),
        ),
    'SingleLogoutService' =>
        array (
            0 =>
                array (
                    'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
                    'Location' => 'http://saml.local/saml2/idp/SingleLogoutService.php',
                ),
        ),
    'ArtifactResolutionService' =>
        array (
        ),
    'keys' =>
        array (
            0 =>
                array (
                    'encryption' => false,
                    'signing' => true,
                    'type' => 'X509Certificate',
                    'X509Certificate' => 'MIIDdTCCAl2gAwIBAgIJAJO/XH8TLlkMMA0GCSqGSIb3DQEBBQUAMFExCzAJBgNVBAYTAlVTMQswCQYDVQQIDAJOQzEPMA0GA1UEBwwGV2F4aGF3MQ8wDQYDVQQKDAZGaWxsdXAxEzARBgNVBAMMCnNhbWwubG9jYWwwHhcNMTQwNzAyMDIxMjE5WhcNMjQwNzAxMDIxMjE5WjBRMQswCQYDVQQGEwJVUzELMAkGA1UECAwCTkMxDzANBgNVBAcMBldheGhhdzEPMA0GA1UECgwGRmlsbHVwMRMwEQYDVQQDDApzYW1sLmxvY2FsMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAnYydqp48b0iW7nccjr6vW4CjmAgNZX+nfnRw1gs68OS2FORI4trSUNk9ZQw9GA9WiWIMMe6SomT+cbU6RiFQpLqigO56RbOgSBXClxN81hjB94OSHDhH0le1uT8nB8MbDMIOVhsRp15kNqpIu5fA+ZX2dti/sdSzn/wCq7H/yIpnqsnFRjVdAybGopFLuBO5EoGDW0s8okRFp7iVQajoksy0lWvHgTDzwfciRogCaKLg85WlU5poDewjE0STnpnXV5sSpcTM+c3A10A0Tt2lTXzV67iMEE6xE5xzJtdvWsoOQkcns0G+V310peZ9TOjaHKGgQVjSiCtSmYZukm7e+QIDAQABo1AwTjAdBgNVHQ4EFgQUngWMgRGmQJl131w2nmYUIRcy2v4wHwYDVR0jBBgwFoAUngWMgRGmQJl131w2nmYUIRcy2v4wDAYDVR0TBAUwAwEB/zANBgkqhkiG9w0BAQUFAAOCAQEAaf6NewMn28axMIxZetsrh2fYsvDSe+yo7ICJBhduE3TiYn5UbLXArlHgBR9gzYrQcwsQPUEKZhcuyky/vvSTykEhgHBhyQBmZpOfuc5ihQ11AWkA2GxQVxkZnemR8HEGgkg+NrMhAf6TiAqifFzsqi5IujJHqCQgPoF53KwH2zadqdM/f1+Jl9/CrBeMS4pX98/VQorBivsgpYcDkxF+rF/JfO2aU91nFKiIr4GeJrBqzJmYpWTR4jPYSRXN/ORmaE103bDtLhok5YkwKZpw28Iw8p1TkMe27kSa56gxqE/1Rr/YA6xTgk3eZhbdFpkaZUP42e0ZCde0BXdYRFSorg==',
                ),
            1 =>
                array (
                    'encryption' => true,
                    'signing' => false,
                    'type' => 'X509Certificate',
                    'X509Certificate' => 'MIIDdTCCAl2gAwIBAgIJAJO/XH8TLlkMMA0GCSqGSIb3DQEBBQUAMFExCzAJBgNVBAYTAlVTMQswCQYDVQQIDAJOQzEPMA0GA1UEBwwGV2F4aGF3MQ8wDQYDVQQKDAZGaWxsdXAxEzARBgNVBAMMCnNhbWwubG9jYWwwHhcNMTQwNzAyMDIxMjE5WhcNMjQwNzAxMDIxMjE5WjBRMQswCQYDVQQGEwJVUzELMAkGA1UECAwCTkMxDzANBgNVBAcMBldheGhhdzEPMA0GA1UECgwGRmlsbHVwMRMwEQYDVQQDDApzYW1sLmxvY2FsMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAnYydqp48b0iW7nccjr6vW4CjmAgNZX+nfnRw1gs68OS2FORI4trSUNk9ZQw9GA9WiWIMMe6SomT+cbU6RiFQpLqigO56RbOgSBXClxN81hjB94OSHDhH0le1uT8nB8MbDMIOVhsRp15kNqpIu5fA+ZX2dti/sdSzn/wCq7H/yIpnqsnFRjVdAybGopFLuBO5EoGDW0s8okRFp7iVQajoksy0lWvHgTDzwfciRogCaKLg85WlU5poDewjE0STnpnXV5sSpcTM+c3A10A0Tt2lTXzV67iMEE6xE5xzJtdvWsoOQkcns0G+V310peZ9TOjaHKGgQVjSiCtSmYZukm7e+QIDAQABo1AwTjAdBgNVHQ4EFgQUngWMgRGmQJl131w2nmYUIRcy2v4wHwYDVR0jBBgwFoAUngWMgRGmQJl131w2nmYUIRcy2v4wDAYDVR0TBAUwAwEB/zANBgkqhkiG9w0BAQUFAAOCAQEAaf6NewMn28axMIxZetsrh2fYsvDSe+yo7ICJBhduE3TiYn5UbLXArlHgBR9gzYrQcwsQPUEKZhcuyky/vvSTykEhgHBhyQBmZpOfuc5ihQ11AWkA2GxQVxkZnemR8HEGgkg+NrMhAf6TiAqifFzsqi5IujJHqCQgPoF53KwH2zadqdM/f1+Jl9/CrBeMS4pX98/VQorBivsgpYcDkxF+rF/JfO2aU91nFKiIr4GeJrBqzJmYpWTR4jPYSRXN/ORmaE103bDtLhok5YkwKZpw28Iw8p1TkMe27kSa56gxqE/1Rr/YA6xTgk3eZhbdFpkaZUP42e0ZCde0BXdYRFSorg==',
                ),
        ),
    'NameIDFormat' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:persistent',
);

