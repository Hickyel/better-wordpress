import React from 'react'
import ReactDOM from 'react-dom/client'
import App from './App'
import './index.css'

const root = document.getElementById('bw-react-root')

if (root) {
  ReactDOM.createRoot(root).render(<App />)
}
