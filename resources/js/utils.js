export const getFullName = (user) => {
    return [user?.first_name, user?.last_name].filter(Boolean).join(" ");
};
