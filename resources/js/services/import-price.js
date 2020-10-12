import { http, httpFile } from "./http_service";

// to create
export function importPriceChange(data) {
    return httpFile().post("/import-price-change", data);
}

export function importBulkPrice(data) {
    return httpFile().post("/import-bulk-price", data);
}

