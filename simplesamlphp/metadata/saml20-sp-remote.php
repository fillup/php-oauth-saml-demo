<?php
/**
 * SAML 2.0 remote SP metadata for simpleSAMLphp.
 *
 * See: http://simplesamlphp.org/docs/trunk/simplesamlphp-reference-sp-remote
 */


$metadata['http://saml.local/module.php/saml/sp/metadata.php/default-sp'] = array(
    'AssertionConsumerService' => 'http://saml.local/module.php/saml/sp/saml2-acs.php/default-sp',
    'SingleLogoutService'      => 'http://saml.local/module.php/saml/sp/saml2-logout.php/default-sp',
    'NameIDFormat' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:persistent',
);

$metadata['http://oauth.local/simplesaml/module.php/saml/sp/metadata.php/oauth-sp'] = array(
    'AssertionConsumerService' => 'http://oauth.local/simplesaml/module.php/saml/sp/saml2-acs.php/oauth-sp',
    'SingleLogoutService'      => 'http://oauth.local/simplesaml/module.php/saml/sp/saml2-logout.php/oauth-sp',
    'NameIDFormat' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:persistent',
);


