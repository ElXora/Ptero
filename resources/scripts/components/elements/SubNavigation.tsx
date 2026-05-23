import styled from 'styled-components/macro';
import tw from 'twin.macro';

const SubNavigation = styled.div`
    width: 100%;
    background: #111111;
    border-bottom: 1px solid rgba(255,255,255,0.07);
    overflow-x: auto;
    box-shadow: 0 2px 12px rgba(0,0,0,0.4);

    & > div {
        ${tw`flex items-center text-sm mx-auto px-2`};
        max-width: 1200px;

        & > a,
        & > div {
            display: inline-block;
            padding: 12px 16px;
            color: rgba(255,255,255,0.45);
            text-decoration: none;
            white-space: nowrap;
            transition: color 0.15s ease, box-shadow 0.15s ease;
            font-size: 0.8rem;
            letter-spacing: 0.02em;
            border-bottom: 2px solid transparent;

            &:not(:first-of-type) {
                ${tw`ml-2`};
            }

            &:hover {
                color: rgba(255,255,255,0.8);
            }

            &:active,
            &.active {
                color: #ffffff;
                border-bottom-color: rgba(255,255,255,0.7);
            }
        }
    }
`;

export default SubNavigation;
