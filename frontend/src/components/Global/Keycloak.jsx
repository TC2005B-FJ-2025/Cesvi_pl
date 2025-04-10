import Keycloak from "keycloak-js";
const keycloak = new Keycloak({
    url: "https://auth.cesvimexico.com.mx:2083",
    //url: "https://auth.sistemagma.com:8443/",
    realm: "Cesvi",
    // realm: "auth",
    clientId: process.env.REACT_APP_clientId,    
});

export default keycloak;