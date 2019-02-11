export default function middleware_log({ next, to }) {
    // eslint-disable-next-line no-console
    console.log("going next", to.name);

    return next();
}