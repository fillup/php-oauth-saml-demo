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
[View Image](http://goo.gl/VtnSsg)


    title OAuth 2.0 with simpleSAMLphp

    participant Resource Owner (User) as User
    participant Client Application (appclient) as Client
    participant Resource Server (appserver) as Server
    participant Authorization Server (php-oauth) as AuthZ
    participant Authentication Server (SAML) as AuthN

    User->Client: Click Login
    Client->AuthZ: OAuth Auth Request
    AuthZ->AuthN: Send User to login
    AuthN->AuthZ: Authenticated, \nSAML attrs included
    AuthZ->Client: Token Request Code
    Client->AuthZ: Request Access Token
    AuthZ->Client: Access Token
    User->Client: Attempt to \nAccess Something
    Client->Server: API Request for Something \nIncluding Bearer Token
    Server->AuthZ: Validate Bearer Token \nand Scopes
    AuthZ->Server: Token Information \nIncluding Scopes
    Server->Client: Token/Scope verified, \nSomething returned
    Client->User: Display Something

