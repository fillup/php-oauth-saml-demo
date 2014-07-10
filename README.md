# php-oauth + simpleSAMLphp + app/api server + client application #
The goal of this project is to implement the [php-oauth](https://github.com/fkooman/php-oauth) OAuth 2.0
authorization server along with simpleSAMLphp and example applications that implement and consume APIs
using OAuth 2.0.

## Installation / Setup ##
This project uses Vagrant with VirtualBox to setup local servers and install the needed software. All you need
to do to test this out is:

1. Clone this repo
2. See block below for ```/etc/hosts``` file entry and add that line to your ```/etc/hosts``` file
3. From the command line, go to the ```vagrant/``` folder in the repo
4. Run ```vagrant up```
5. Once vagrant is done spinning up the virtual machine, open your browser and go to
[http://appclient.local/](http://appclient.local/)
6. You should see a simple page, click the Login link in the header and you should be redirected to login and grant
access.
7. Login with username ```user1``` and password ```pass1```.
8. After being logged in you'll see a sort of debug page showing the output of a few API calls being made
to the appserver.local host.


    # /etc/hosts
    # OAuth POC hosts
    192.168.55.10 saml.local oauth.local demo.local appserver.local appclient.local


### Web Sequence Diagram ###
[View Image](http://goo.gl/7nxMtv)


    title OAuth 2.0 with simpleSAMLphp

    participant Resource Owner (User) as User
    participant Client Application (appclient) as Client
    participant Resource Server 1 (appserver) as Server1
    participant Authorization Server (php-oauth) as AuthZ
    participant Authentication Server (SAML) as AuthN

    note right of User
    Solid lines are browser based calls.
    end note

    User->Client: Click Login
    Client->User: Redirect user to OAuth Auth Request
    User->AuthZ: Route to login url
    AuthZ->User: Redirect User to\nlogin via SAML
    User->AuthN: Login via SAML
    AuthN->User: Redirect to OAuth Server
    User->AuthZ: Authenticated,\nSAML attrs included
    AuthZ->User: Prompt to grant access to requested scopes
    User->AuthZ: Access Granted
    AuthZ->User: Redirect user to Client with Token Request Code
    User->Client: Successfully logged in,\nAuth Code included

    note over Server1
    Dashed lines are server to server API calls.
    end note

    Client-->AuthZ: Request Access Token
    AuthZ-->Client: Access Token
    User->Client: Attempt to\nAccess Something
    Client-->Server1: API Request for Something \nIncluding Bearer Token
    Server1-->AuthZ: Validate Bearer Token\nand Scopes
    AuthZ-->Server1: Token Information\nIncluding Scopes
    Server1-->Client: Token/Scope verified,\nSomething returned
    Client->User: Display Something










