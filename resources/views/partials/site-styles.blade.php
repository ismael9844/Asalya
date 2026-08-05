* { font-family: 'Inter', sans-serif; }

:root {
    --primary-blue: #0ea5e9;
    --primary-blue-dark: #0284c7;
}

body {
    transition: background-color 0.3s ease, color 0.3s ease;
    margin: 0;
    padding: 0;
}

body.dark-mode { background-color: #0f172a; color: #f1f5f9; }
body.dark-mode .bg-white { background-color: #1e293b !important; }
body.dark-mode .bg-gray-50 { background-color: #1a2332 !important; }
body.dark-mode .text-gray-800 { color: #e2e8f0 !important; }
body.dark-mode .text-gray-600 { color: #94a3b8 !important; }
body.dark-mode .border-gray-200,
body.dark-mode .border-gray-100 { border-color: #334155 !important; }

.glass-effect {
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    border: 1px solid rgba(255, 255, 255, 0.3);
}

body.dark-mode .glass-effect {
    background: rgba(15, 23, 42, 0.85);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

::-webkit-scrollbar { width: 10px; }
::-webkit-scrollbar-track { background: #f1f5f9; }
body.dark-mode ::-webkit-scrollbar-track { background: #1e293b; }
::-webkit-scrollbar-thumb { background: #0ea5e9; border-radius: 5px; }
::-webkit-scrollbar-thumb:hover { background: #0284c7; }

.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

html { scroll-behavior: smooth; }
