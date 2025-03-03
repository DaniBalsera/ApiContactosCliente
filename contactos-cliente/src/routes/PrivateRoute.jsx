import { useContext, useEffect, useState } from 'react';
import { AuthContext } from '../context/AuthContext';
import { Navigate } from 'react-router-dom';

function PrivateRoute({ children }) {
  const { token, user } = useContext(AuthContext);
  const [isLoading, setIsLoading] = useState(true);

  useEffect(() => {
    if (token && user) {
      setIsLoading(false);
    } else {
      setIsLoading(true);
    }
  }, [token, user]);

  if (isLoading) {
    return <div>Loading...</div>;
  }

  if (!token || !user) {
    return <Navigate to="/login" />;
  }

  return children;
}

export default PrivateRoute;