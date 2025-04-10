import axios from 'axios';

//keycloak.subject = id de usuario
const clienteAxios = axios.create(
  {
    baseURL: process.env.REACT_APP_BASE_URL, 
    headers: {    
      'Content-Type': 'application/json',
      'X-Requested-With': 'XMLHttpRequest',
    },  
  }
);

export default clienteAxios;