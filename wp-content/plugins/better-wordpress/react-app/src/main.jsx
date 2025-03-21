import React from 'react'
import ReactDOM from 'react-dom/client'
import DashboardApp from './components/DashboardApp'
import SettingsApp from './components/SettingsApp'

const dashboardRoot = document.getElementById('bw-dashboard-root')
const settingsRoot = document.getElementById('bw-settings-root')

if (dashboardRoot) {
  ReactDOM.createRoot(dashboardRoot).render(<DashboardApp />)
}

if (settingsRoot) {
  ReactDOM.createRoot(settingsRoot).render(<SettingsApp />)
}
