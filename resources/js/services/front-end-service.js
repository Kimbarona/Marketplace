import { http, httpFile } from "./http_service";

// to retrieve
// export function retrieveData() {
//     return http().get("/meals");
// }

// to search
export function retrieveSearchData(meat_type) {
    if (!meat_type) {
        return http().get("/meals");
    } else {
        return httpFile().get(`meals/${meat_type}`);
    }
}
