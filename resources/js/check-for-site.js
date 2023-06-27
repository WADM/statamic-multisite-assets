Statamic.booting(() => {
    Statamic.$conditions.add('isOnSite', ({ target, params }) => {
        const site = Statamic.$config.get('selectedSite');
        return params.includes(site);
    });
});