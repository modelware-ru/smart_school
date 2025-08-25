const validateEmail = (email) => {
    return String(email)
        .toLowerCase()
        .match(
            /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|.(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
        );
};

const openSiteURL = (pathname = 'index.php', paramList = null) => {
    let url = pathname;

    if (paramList) {
        const searchParams = new URLSearchParams();
        Object.keys(paramList).forEach((key) => searchParams.append(key, paramList[key]));
        url = `${url}?${searchParams.toString()}`;
    }
    window.location.assign(url);
};

export { validateEmail, openSiteURL };
