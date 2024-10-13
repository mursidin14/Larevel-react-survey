import {createContext, useContext, useState} from 'react';

const userStateContext = createContext({
    user: null,
    token: null,
    setUser: () => {},
    setToken: () => {},
});

export default function UserContext({children}) {
    const [user, setUser] = useState(null);
    const [token, _setToken] = useState(localStorage.getItem("access_token") || null);

    const setToken = (token) => {
        _setToken(token);

        if(token){
            localStorage.setItem("access_token", token);
        } else {
            localStorage.removeItem("access_token");
        }
    }
  return (
    <userStateContext.Provider value={{ 
        user,
        setUser,
        token,
        setToken
     }}>
        {children}
    </userStateContext.Provider>
  )
}

export const useUserContext = () => {
    return useContext(userStateContext);
}
