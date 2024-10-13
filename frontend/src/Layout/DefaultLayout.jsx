import { Outlet, Navigate } from "react-router-dom";
import { useUserContext } from "../context/UserContext";

export default function DefaultLayout() {
    const { token } = useUserContext();

    if(token) {
        return <Navigate to='/' />
    }
  return (
    <>
        <main>
            <Outlet />
        </main>
    </>
  )
}
