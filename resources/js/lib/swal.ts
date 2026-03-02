import Swal from 'sweetalert2';

export const confirmDelete = (title = 'Hapus?', text = 'Data akan dihapus.') => {
    const isDark = document.documentElement.classList.contains('dark');

    return Swal.fire({
        title,
        text,
        icon: 'warning',
        iconColor: '#f59e0b',
        showCancelButton: true,
        confirmButtonText: 'Hapus',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        
        width: '300px',
        padding: '1.5rem', 
        background: isDark ? '#0f172a' : '#ffffff', 
        color: isDark ? '#f1f5f9' : '#1e293b',
        buttonsStyling: false, 

        customClass: {
            popup: 'rounded-[2.5rem] border-none shadow-2xl',
            icon: 'scale-75 !m-0 !mb-2 justify-self-center', 
            title: '!text-sm !font-black tracking-tight !p-0 !m-0', 
            htmlContainer: '!text-[10px] !font-medium opacity-60 !mt-1 !mb-0 line-relaxed',
            actions: 'w-full gap-3 !mt-5 !mb-0 px-2', 
            
            confirmButton: `
                h-10 px-6 flex-1 inline-flex items-center justify-center rounded-xl text-xs font-bold transition-all 
                bg-gradient-to-tr from-indigo-600 to-indigo-500 text-white shadow-lg shadow-indigo-200 dark:shadow-indigo-900/40 
                hover:from-indigo-700 hover:to-indigo-600 border-t border-white/20 active:scale-95
            `,
            cancelButton: `
                h-10 px-6 flex-1 inline-flex items-center justify-center rounded-xl text-xs font-bold transition-all 
                bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700 active:scale-95
            `
        },
        showClass: { popup: 'animate__animated animate__zoomIn animate__faster' },
        hideClass: { popup: 'animate__animated animate__zoomOut animate__faster' }
    });
};

export const mobileToast = (
  message: string,
  type: 'success' | 'error' | 'warning' = 'success'
) => {
  const isDark = document.documentElement.classList.contains('dark');

  const colors = {
    success: isDark ? '#34d399' : '#10b981',
    error: isDark ? '#f87171' : '#dc2626',
    warning: isDark ? '#fbbf24' : '#ca8a04',
  };

  const icons = {
    success: `
      <svg xmlns="http://www.w3.org/2000/svg"
        fill="none" viewBox="0 0 24 24" stroke-width="2"
        stroke="${colors.success}"
        style="width:18px;height:18px;">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M4.5 12.75l6 6 9-13.5"/>
      </svg>
    `,
    error: `
      <svg xmlns="http://www.w3.org/2000/svg"
        fill="none" viewBox="0 0 24 24" stroke-width="2"
        stroke="${colors.error}"
        style="width:18px;height:18px;">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M6 18L18 6M6 6l12 12"/>
      </svg>
    `,
    warning: `
      <svg xmlns="http://www.w3.org/2000/svg"
        fill="none" viewBox="0 0 24 24" stroke-width="2"
        stroke="${colors.warning}"
        style="width:18px;height:18px;">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M12 9v3m0 3h.01M4.93 19h14.14c1.54 0 2.5-1.67 
          1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.2 
          16c-.77 1.33.19 3 1.73 3z"/>
      </svg>
    `,
  };

  return Swal.fire({
    html: `
      <div style="
        display:flex;
        align-items:center;
        gap:8px;
      ">
        ${icons[type]}
        <span style="
          font-size:12px;
          font-weight:600;
          letter-spacing:-0.2px;
        ">
          ${message}
        </span>
      </div>
    `,
    toast: true,
    position: 'top',
    showConfirmButton: false,
    timer: 2200,
    background: isDark ? 'rgba(30,41,59,0.9)' : 'rgba(30,41,59,0.1)',
    // background: isDark ? 'rgba(255,255,255,0.08)' : 'rgba(255,255,255)',
    color: isDark ? '#fff' : '#1e293b',

    customClass: {
      popup: `
        !w-auto !max-w-[88vw]
        !px-1 !py-1
        !rounded-2xl
        !border !border-white/20
        !shadow-xl
      `,
    },

    didOpen: (el) => {
    el.style.backdropFilter = 'blur(20px) saturate(180%)';
    el.style.setProperty(
        '-webkit-backdrop-filter',
        'blur(20px) saturate(180%)'
    );
    },

    showClass: {
      popup: 'animate__animated animate__fadeInUp animate__faster'
    },
    hideClass: {
      popup: 'animate__animated animate__fadeOutDown animate__faster'
    }
  });
};