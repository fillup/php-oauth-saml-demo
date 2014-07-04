## SURFconext
If you want to use SURFconext as an IdP the configuration needs to be like the 
snippet below:

    $metadata['https://engine.surfconext.nl/authentication/idp/metadata'] = array(
        'SingleSignOnService' => 'https://engine.surfconext.nl/authentication/idp/single-sign-on',
        'certFingerprint' => 'a36aac83b9a552b3dc724bfc0d7bba6283af5f8e',

        'authproc' => array(
            50 => array(
                'class' => 'core:AttributeMap',
                'oid2name',
            ),
        ),

    );

`php-oauth` will use a persistent Name ID as a unique identifier for a user, so
no attribute is used for that. 
