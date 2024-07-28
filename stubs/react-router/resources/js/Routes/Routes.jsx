import {
    createBrowserRouter,
} from "react-router-dom";
import Guest from "@/Layouts/GuestLayout.jsx";

const routes = createBrowserRouter([
    {
        element: <Guest />,
        path: "/",
        children: [
            {

            },
        ]
    }
]);

export default routes;
