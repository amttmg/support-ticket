import Swal from 'sweetalert2'

export default {
    confirm: (options) => {
        return Swal.fire({
            title: options.title || 'Are you sure?',
            text: options.text || '',
            icon: options.icon || 'warning',
            showCancelButton: true,
            confirmButtonColor: options.confirmColor || '#3085d6',
            cancelButtonColor: options.cancelColor || '#d33',
            confirmButtonText: options.confirmText || 'Confirm',
            cancelButtonText: options.cancelText || 'Cancel',
            ...options
        })
    },

    success: (message) => {
        return Swal.fire('Success!', message, 'success')
    },

    error: (message) => {
        return Swal.fire('Error!', message, 'error')
    }
}