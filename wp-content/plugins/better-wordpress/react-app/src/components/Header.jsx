import React from "react";

const Header = () => {
    return (
        <header style={{
            background: '#2271b1',
            color: '#fff',
            padding: '1rem 2rem',
            borderRadius: '8px',
            marginBottom: '2rem'
          }}>
            <h1 style={{ margin: 0 }}>⚙️ Better WordPress</h1>
            <p style={{ margin: 0, fontSize: '0.9rem' }}>Ton interface d’administration évoluée</p>
          </header>
    );
}

export default Header;