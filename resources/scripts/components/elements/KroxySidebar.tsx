import React, { useState } from 'react';
import { NavLink } from 'react-router-dom';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import {
    faTerminal, faFolder, faDatabase, faClock, faUsers,
    faArchive, faNetworkWired, faRocket, faCog, faHistory,
    faUser, faKey, faSshKeyboard, faChevronLeft, faChevronRight,
    faHome, faTachometerAlt, faShieldAlt,
} from '@fortawesome/free-solid-svg-icons';

const iconMap: Record<string, any> = {
    'Console':      faTerminal,
    'Files':        faFolder,
    'Databases':    faDatabase,
    'Schedules':    faClock,
    'Users':        faUsers,
    'Backups':      faArchive,
    'Network':      faNetworkWired,
    'Startup':      faRocket,
    'Settings':     faCog,
    'Activity':     faHistory,
    // account
    'Account':      faUser,
    'API Credentials': faKey,
    'SSH Keys':     faSshKeyboard,
    // dashboard
    'Dashboard':    faTachometerAlt,
    'Admin':        faShieldAlt,
};

interface SidebarLink {
    path: string;
    name: string;
    exact?: boolean;
}

interface Props {
    links: SidebarLink[];
    baseUrl?: string;
    title?: string;
    extra?: React.ReactNode;
}

const KroxySidebar: React.FC<Props> = ({ links, baseUrl = '', title, extra }) => {
    const [collapsed, setCollapsed] = useState(false);

    return (
        <aside
            style={{
                width: collapsed ? '56px' : '210px',
                minWidth: collapsed ? '56px' : '210px',
                background: '#0d0d0d',
                borderRight: '1px solid rgba(255,255,255,0.07)',
                display: 'flex',
                flexDirection: 'column',
                transition: 'width 0.22s cubic-bezier(0.4,0,0.2,1), min-width 0.22s cubic-bezier(0.4,0,0.2,1)',
                position: 'sticky',
                top: 0,
                height: 'calc(100vh - 3.5rem - 21px)',  /* topbar height + powered-by strip */
                overflowY: 'auto',
                overflowX: 'hidden',
                zIndex: 30,
                flexShrink: 0,
            }}
        >
            {/* Sidebar header */}
            {!collapsed && title && (
                <div style={{
                    padding: '16px 18px 8px',
                    fontSize: '0.6rem',
                    letterSpacing: '0.16em',
                    textTransform: 'uppercase',
                    color: 'rgba(255,255,255,0.25)',
                    borderBottom: '1px solid rgba(255,255,255,0.05)',
                    marginBottom: '4px',
                    whiteSpace: 'nowrap',
                    overflow: 'hidden',
                }}>
                    {title}
                </div>
            )}

            {/* Nav links */}
            <nav style={{ flex: 1, padding: '8px 0' }}>
                {links.map(({ path, name, exact }) => {
                    const icon = iconMap[name] || faCog;
                    const to = baseUrl ? `${baseUrl}/${path}`.replace(/\/+/g, '/') : path;
                    return (
                        <NavLink
                            key={path}
                            to={to}
                            exact={exact}
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
                            onMouseEnter={e => {
                                const el = e.currentTarget;
                                if (!el.classList.contains('active')) {
                                    el.style.color = 'rgba(255,255,255,0.85)';
                                    el.style.background = 'rgba(255,255,255,0.04)';
                                }
                            }}
                            onMouseLeave={e => {
                                const el = e.currentTarget;
                                if (!el.classList.contains('active')) {
                                    el.style.color = 'rgba(255,255,255,0.45)';
                                    el.style.background = 'transparent';
                                }
                            }}
                        >
                            <span style={{ width: '16px', flexShrink: 0, opacity: 0.75, textAlign: 'center' }}>
                                <FontAwesomeIcon icon={icon} />
                            </span>
                            {!collapsed && (
                                <span style={{ overflow: 'hidden', textOverflow: 'ellipsis' }}>
                                    {name}
                                </span>
                            )}
                        </NavLink>
                    );
                })}
                {extra}
            </nav>

            {/* Powered by + collapse button */}
            <div style={{
                borderTop: '1px solid rgba(255,255,255,0.05)',
                padding: '10px 0',
            }}>
                {!collapsed && (
                    <div style={{
                        textAlign: 'center',
                        fontSize: '0.55rem',
                        color: 'rgba(255,255,255,0.15)',
                        letterSpacing: '0.1em',
                        textTransform: 'uppercase',
                        padding: '4px 0 8px',
                    }}>
                        Powered by Pterodactyl
                    </div>
                )}
                <button
                    onClick={() => setCollapsed(c => !c)}
                    style={{
                        display: 'flex',
                        alignItems: 'center',
                        justifyContent: collapsed ? 'center' : 'flex-end',
                        width: '100%',
                        padding: '6px 16px',
                        background: 'none',
                        border: 'none',
                        color: 'rgba(255,255,255,0.25)',
                        cursor: 'pointer',
                        transition: 'color 0.15s',
                        fontSize: '0.75rem',
                    }}
                    onMouseEnter={e => (e.currentTarget.style.color = 'rgba(255,255,255,0.7)')}
                    onMouseLeave={e => (e.currentTarget.style.color = 'rgba(255,255,255,0.25)')}
                    title={collapsed ? 'Expand sidebar' : 'Collapse sidebar'}
                >
                    <FontAwesomeIcon icon={collapsed ? faChevronRight : faChevronLeft} />
                    {!collapsed && <span style={{ marginLeft: '8px', fontSize: '0.65rem', letterSpacing: '0.05em' }}>Collapse</span>}
                </button>
            </div>
        </aside>
    );
};

export default KroxySidebar;
