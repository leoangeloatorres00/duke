import { usePage } from "@inertiajs/vue3";
import { useApi } from "./useApi.js";

import { ROUTE } from "@/Constants/Routes.js";

export function useSite() {
    const page = usePage();

    const { getData } = useApi();

    async function getSiteData() {
        const url = ROUTE.SITE_DATA;
        const params = { user_id: page.props.auth.user.user_id };

        try {
            const response = await getData(route(url), params);

            return response.data;
        } catch (err) {
            console.log(err);
        }
    }

    return {
        getSiteData,
    };
}
