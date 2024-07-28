import './bootstrap';
import '../css/app.css';

import React from "react"
import ReactDOM from 'react-dom/client';
import { RouterProvider } from "react-router-dom";
import Routes from "./components/Routes/Routes.jsx";

ReactDOM.createRoot(document.getElementById('root')).render(
    <React.StrictMode>
        <RouterProvider router={Routes} />
    </React.StrictMode>
);
