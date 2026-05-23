import TransferListener from '@/components/server/TransferListener';
import React, { useEffect, useState } from 'react';
import { NavLink, Route, Switch, useRouteMatch } from 'react-router-dom';
import NavigationBar from '@/components/NavigationBar';
import TransitionRouter from '@/TransitionRouter';
import WebsocketHandler from '@/components/server/WebsocketHandler';
import { ServerContext } from '@/state/server';
import { CSSTransition } from 'react-transition-group';
import Can from '@/components/elements/Can';
import Spinner from '@/components/elements/Spinner';
import { NotFound, ServerError } from '@/components/elements/ScreenBlock';
import { httpErrorToHuman } from '@/api/http';
import { useStoreState } from 'easy-peasy';
import InstallListener from '@/components/server/InstallListener';
import ErrorBoundary from '@/components/elements/ErrorBoundary';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faExternalLinkAlt } from '@fortawesome/free-solid-svg-icons';
import { useLocation } from 'react-router';
import ConflictStateRenderer from '@/components/server/ConflictStateRenderer';
import PermissionRoute from '@/components/elements/PermissionRoute';
import routes from '@/routers/routes';
import KroxySidebar from '@/components/elements/KroxySidebar';
import { ApplicationStore } from '@/state';

export default () => {
    const match = useRouteMatch<{ id: string }>();
    const location = useLocation();

    const rootAdmin = useStoreState((state: ApplicationStore) => state.user.data!.rootAdmin);
    const [error, setError] = useState('');

    const id = ServerContext.useStoreState((state) => state.server.data?.id);
    const uuid = ServerContext.useStoreState((state) => state.server.data?.uuid);
    const inConflictState = ServerContext.useStoreState((state) => state.server.inConflictState);
    const serverId = ServerContext.useStoreState((state) => state.server.data?.internalId);
    const serverName = ServerContext.useStoreState((state) => state.server.data?.name);
    const getServer = ServerContext.useStoreActions((actions) => actions.server.getServer);
    const clearServerState = ServerContext.useStoreActions((actions) => actions.clearServerState);

    const to = (value: string, url = false) => {
        if (value === '/') {
            return url ? match.url : match.path;
        }
        return `${(url ? match.url : match.path).replace(/\/*$/, '')}/${value.replace(/^\/+/, '')}`;
    };

    useEffect(
        () => () => {
            clearServerState();
        },
        []
    );

    useEffect(() => {
        setError('');
        getServer(match.params.id).catch((error) => {
            console.error(error);
            setError(httpErrorToHuman(error));
        });
        return () => {
            clearServerState();
        };
    }, [match.params.id]);

    const sidebarLinks = routes.server
        .filter((route) => !!route.name)
        .map((route) => ({
            path: to(route.path, true),
            name: route.name as string,
            exact: route.exact,
            permission: route.permission,
        }));

    return (
        <div style={{ display: 'flex', flexDirection: 'column', minHeight: '100vh' }}>
            <NavigationBar />
            {!uuid || !id ? (
                error ? (
                    <ServerError message={error} />
                ) : (
                    <Spinner size={'large'} centered />
                )
            ) : (
                <div style={{ display: 'flex', flex: 1 }}>
                    {/* Sidebar */}
                    <KroxySidebar
                        links={[]}
                        title={serverName || 'Server'}
                        extra={
                            <>
                                {routes.server
                                    .filter((route) => !!route.name)
                                    .map((route) =>
                                        route.permission ? (
                                            <Can key={route.path} action={route.permission} matchAny>
                                                <NavLink
                                                    to={to(route.path, true)}
                                                    exact={route.exact}
                                                    activeStyle={{
                                                        background: 'rgba(255,255,255,0.07)',
                                                        color: '#ffffff',
                                                        borderRight: '2px solid rgba(255,255,255,0.6)',
                                                    }}
                                                    style={{
                                                        display: 'flex',
                                                        alignItems: 'center',
                                                        gap: '12px',
                                                        padding: '10px 18px',
                                                        color: 'rgba(255,255,255,0.45)',
                                                        textDecoration: 'none',
                                                        fontSize: '0.8rem',
                                                        letterSpacing: '0.02em',
                                                        whiteSpace: 'nowrap',
                                                        overflow: 'hidden',
                                                        transition: 'color 0.15s ease, background 0.15s ease',
                                                        borderRight: '2px solid transparent',
                                                    }}
                                                >
                                                    {route.name}
                                                </NavLink>
                                            </Can>
                                        ) : (
                                            <NavLink
                                                key={route.path}
                                                to={to(route.path, true)}
                                                exact={route.exact}
                                                activeStyle={{
                                                    background: 'rgba(255,255,255,0.07)',
                                                    color: '#ffffff',
                                                    borderRight: '2px solid rgba(255,255,255,0.6)',
                                                }}
                                                style={{
                                                    display: 'flex',
                                                    alignItems: 'center',
                                                    gap: '12px',
                                                    padding: '10px 18px',
                                                    color: 'rgba(255,255,255,0.45)',
                                                    textDecoration: 'none',
                                                    fontSize: '0.8rem',
                                                    letterSpacing: '0.02em',
                                                    whiteSpace: 'nowrap',
                                                    overflow: 'hidden',
                                                    transition: 'color 0.15s ease, background 0.15s ease',
                                                    borderRight: '2px solid transparent',
                                                }}
                                            >
                                                {route.name}
                                            </NavLink>
                                        )
                                    )}
                                {rootAdmin && (
                                    <a
                                        href={`/admin/servers/view/${serverId}`}
                                        target={'_blank'}
                                        rel={'noreferrer'}
                                        style={{
                                            display: 'flex',
                                            alignItems: 'center',
                                            gap: '10px',
                                            padding: '10px 18px',
                                            color: 'rgba(255,255,255,0.3)',
                                            textDecoration: 'none',
                                            fontSize: '0.75rem',
                                            whiteSpace: 'nowrap',
                                            borderTop: '1px solid rgba(255,255,255,0.05)',
                                            marginTop: '4px',
                                        }}
                                    >
                                        <FontAwesomeIcon icon={faExternalLinkAlt} />
                                        <span>Admin View</span>
                                    </a>
                                )}
                            </>
                        }
                    />

                    {/* Main content */}
                    <main style={{ flex: 1, minWidth: 0 }}>
                        <InstallListener />
                        <TransferListener />
                        <WebsocketHandler />
                        {inConflictState && (!rootAdmin || (rootAdmin && !location.pathname.endsWith(`/server/${id}`))) ? (
                            <ConflictStateRenderer />
                        ) : (
                            <ErrorBoundary>
                                <TransitionRouter>
                                    <Switch location={location}>
                                        {routes.server.map(({ path, permission, component: Component }) => (
                                            <PermissionRoute key={path} permission={permission} path={to(path)} exact>
                                                <Spinner.Suspense>
                                                    <Component />
                                                </Spinner.Suspense>
                                            </PermissionRoute>
                                        ))}
                                        <Route path={'*'} component={NotFound} />
                                    </Switch>
                                </TransitionRouter>
                            </ErrorBoundary>
                        )}
                    </main>
                </div>
            )}
        </div>
    );
};
