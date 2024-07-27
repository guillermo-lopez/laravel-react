import './bootstrap';
import '../css/app.css';


import React from "react"
import ReactDOM from 'react-dom/client';
import {BrowserRouter, RouterProvider} from "react-router-dom";
import Routes from "./components/Routes/Routes.jsx";
import {QueryClient, QueryClientProvider} from "@tanstack/react-query";
const queryClient = new QueryClient();

ReactDOM.createRoot(document.getElementById('root')).render(
    <React.StrictMode>
        <QueryClientProvider client={queryClient}>
            <RouterProvider router={Routes} />
        </QueryClientProvider>
    </React.StrictMode>
);
