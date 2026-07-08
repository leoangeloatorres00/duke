import { usePage } from "@inertiajs/vue3";
import { useApi } from "./useApi.js";

import { ROUTE } from "@/Constants/Routes.js";

export function useEquipment() {
    const page = usePage();

    const { getData } = useApi();

    async function getEquipmentData() {
        const url = ROUTE.EQUIPMENT_DATA;
        const params = { user_id: page.props.auth.user.user_id };

        try {
            const response = await getData(route(url), params);

            return response;
        } catch (err) {
            console.log(err);
        }
    }

    async function getRegisteredEquipmentData(site_id) {
        const url = ROUTE.REG_EQUIPMENT_DATA;
        const params = { site_id: site_id };

        try {
            const response = await getData(route(url), params);

            return response;
        } catch (err) {
            console.log(err);
        }
    }

    return {
        getEquipmentData,
        getRegisteredEquipmentData,
    };
}
