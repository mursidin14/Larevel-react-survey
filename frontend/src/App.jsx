import { RouterProvider } from "react-router-dom"
import UserContext from "./context/UserContext"
import { router } from "./routes"

function App() {

  return (
    <UserContext>
      <RouterProvider router={router} />
    </UserContext>
  )
}

export default App
