import React from 'react';
import { NavLink, Route, Switch, useRouteMatch } from 'react-router-dom';
import NavigationBar from '@/components/NavigationBar';
import DashboardContainer from '@/components/dashboard/DashboardContainer';
import { NotFound } from '@/components/elements/ScreenBlock';
import TransitionRouter from '@/TransitionRouter';
import { useLocation } from 'react-router';
import Spinner from '@/components/elements/Spinner';
import routes from '@/routers/routes';
import KroxySidebar from '@/components/elements/KroxySidebar';
import { useStoreState } from 'easy-peasy';
import { ApplicationStore } from '@/state';

const accountLinks = routes.account.filter(r => !!r.name).map(r => ({
    path: `/account/${r.path}`.replace('//', '/'),
    name: r.name as string,
    exact: r.exact,
}));

const dashboardLinks = [
    { path: '/', name: 'Dashboard', exact: true },
    ...accountLinks,
];

export default () => {
    const location = useLocation();
    const rootAdmin = useStoreState((state: ApplicationStore) => state.user.data!.rootAdmin);

    const sidebarLinks = [
        ...dashboardLinks,
        ...(rootAdmin ? [{ path: '/admin', name: 'Admin', exact: false }] : []),
    ];

    return (
        <div style={{ display: 'flex', flexDirection: 'column', minHeight: '100vh' }}>
            <NavigationBar />
            <div style={{ display: 'flex', flex: 1 }}>
                <KroxySidebar
                    links={sidebarLinks}
                    title={'Navigation'}
                />
                <main style={{ flex: 1, minWidth: 0 }}>
                    <TransitionRouter>
                        <React.Suspense fallback={<Spinner centered />}>
                            <Switch location={location}>
                                <Route path={'/'} exact>
                                    <DashboardContainer />
                                </Route>
                                {routes.account.map(({ path, component: Component }) => (
                                    <Route key={path} path={`/account/${path}`.replace('//', '/')} exact>
                                        <Component />
                                    </Route>
                                ))}
                                <Route path={'*'}>
                                    <NotFound />
                                </Route>
                            </Switch>
                        </React.Suspense>
                    </TransitionRouter>
                </main>
            </div>
        </div>
    );
};
