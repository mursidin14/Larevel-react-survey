import { Outlet, Navigate } from "react-router-dom";
import { useUserContext } from "../context/UserContext";
import Navbar from "../components/Navbar";
import Footer from "../components/Footer";

export default function GuestLayout() {
    const { token } = useUserContext();

    if(!token) {
       return <Navigate to='/login' />
    }
  return (
    <>
        <Navbar />
        <main>
            <Outlet />
        </main>
        <Footer />          
    </>
  )
}
