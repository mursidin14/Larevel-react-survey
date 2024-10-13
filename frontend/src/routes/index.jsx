import { createBrowserRouter } from "react-router-dom";
import NotFound from "../pages/NotFound";
import DefaultLayout from "../Layout/DefaultLayout";
import Home from "../pages/Home";
import GuestLayout from "../Layout/GuestLayout";
import Login from "../pages/Auth/Login";
import Register from "../pages/Auth/Register";

export const router = createBrowserRouter([
    {
        element:<GuestLayout />,
        children: [
            {
                path:'/',
                element:<Home />
            }
        ]
    },
    {
        element: <DefaultLayout />,
        children: [
            {
                path:'/login',
                element:<Login />
            },
            {
                path:'/register',
                element:<Register />
            }
        ]
    },
    {
        path:'*',
        element:<NotFound />
    }
])