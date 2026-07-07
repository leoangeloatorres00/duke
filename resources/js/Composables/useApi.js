import { ref } from "vue";

import axios from "axios";

export function useApi() {
    const data = ref(null);
    const error = ref(null);

    async function request(method, endpoint, payload = null, config = {}) {
        error.value = null;
        try {
            let response;

            if (["get", "delete"].includes(method.toLowerCase())) {
                response = await axios[method.toLowerCase()](endpoint, {
                    ...config,
                });
            } else {
                response = await axios[method.toLowerCase()](
                    endpoint,
                    payload,
                    config,
                );
            }

            data.value = response.data;
            return response.data;
        } catch (err) {
            error.value =
                err.response?.data?.message || err.message || "API Error";
            throw err;
        }
    }

    const getData = (endpoint, params = {}) =>
        request("get", endpoint, null, { params });
    const postData = (endpoint, body) => request("post", endpoint, body);
    const putData = (endpoint, body) => request("put", endpoint, body);
    const patchData = (endpoint, body) => request("patch", endpoint, body);
    const deleteData = (endpoint) => request("delete", endpoint);

    return {
        data,
        error,
        getData,
        postData,
        putData,
        patchData,
        deleteData,
    };
}
