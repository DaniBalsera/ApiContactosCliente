import React from 'react';
import Navbar from './Navbar';

const Layout = ({ children }) => {
  return (
    <div>
      <Navbar />
      <main style={{ margin: '20px' }}>
        {children}
      </main>
    </div>
  );
};

export default Layout;