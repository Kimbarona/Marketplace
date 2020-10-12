import axios from "axios";

let Api = axios.create({
    baseURL: "http://10.10.12.11:8181/api"
});

Api.defaults.withCredentials = true;

export default Api;
