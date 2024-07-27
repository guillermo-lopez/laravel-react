import {
    createBrowserRouter, Link,
} from "react-router-dom";

const routes = createBrowserRouter([
    {
        element: <Layout />,
        path: "/",
        children: [
            {
                path: "jobs",
                children: [
                    {
                        path: "",
                        element: <Jobs/>,
                        handle: { crumb: () => <Link to="jobs">Home</Link>},
                    },
                    {
                        element: <Create/>,
                        path: "create",
                        handle: { crumb: () => <Link to="jobs">Home</Link>}
                    },
                    {
                        path: ":jobId",
                        element: <Job />,
                        handle: { crumb: () => <Link to="jobs">Home</Link>}
                    }
                ],
            },
        ]
    }
]);

export default routes;
