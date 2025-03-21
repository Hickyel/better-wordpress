import React from 'react'
import { HashRouter, Routes, Route, NavLink } from 'react-router-dom'

import Overview from '../pages/Overview'
import Stats from '../pages/Stats'

const DashboardApp = () => {
  return (
    <HashRouter>
      <div className="p-6 font-sans">
        <h2 className="text-2xl font-bold mb-4">⚙️ Better WP Dashboard</h2>

        <nav className="flex gap-4 border-b pb-2 mb-4">
          <NavLink
            to="/"
            className={({ isActive }) =>
              isActive ? "font-bold text-blue-600" : "text-gray-500"
            }
            end
          >
            Aperçu
          </NavLink>
          <NavLink
            to="/stats"
            className={({ isActive }) =>
              isActive ? "font-bold text-blue-600" : "text-gray-500"
            }
          >
            Statistiques
          </NavLink>
        </nav>

        <Routes>
          <Route path="/" element={<Overview />} />
          <Route path="/stats" element={<Stats />} />
        </Routes>
      </div>
    </HashRouter>
  )
}

export default DashboardApp
