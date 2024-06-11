<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table id="users-table"
                        class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead
                            class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" hidden class="px-6 py-3"></th>
                                <th scope="col" class="px-6 py-3">ID</th>
                                <th scope="col" class="px-6 py-3">Name</th>
                                <th scope="col" class="px-6 py-3">Email</th>
                                <th scope="col" class="px-6 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Usuarios se cargarán aquí -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="edit-user-modal" class="hidden fixed z-50 inset-0 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen">
            <div class="modal-background absolute inset-0 bg-gray-500 opacity-75"></div>
            <div class="modal-content p-6 rounded-lg shadow-xl" style="z-index: 100">
                <!-- Formulario de edición del usuario -->
                <form id="edit-user-form">
                    @csrf
                    <!-- Campos de formulario para editar el usuario -->
                </form>
            </div>
        </div>
    </div>

    <div id="success-message"
        class="hidden fixed top-0 left-0 right-0 bg-green-500 text-white text-center p-4">
        ¡Usuario actualizado exitosamente!
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Hacer una solicitud Ajax para obtener usuarios
            fetch('/get-users')
                .then(response => response.json())
                .then(data => {
                    // Actualizar la tabla con los usuarios obtenidos
                    const users = data.users;
                    const tableBody = document.querySelector('#users-table tbody');
                    tableBody.innerHTML = '';

                    users.forEach(user => {
                        const row = document.createElement('tr');
                        row.setAttribute('data-user-id', `${user.id}`);
                        row.innerHTML = `
                            @csrf

    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">${user.id}</th>
    <td class="px-6 py-4">${user.name}</td>
    <td class="px-6 py-4">${user.email}</td>
    <td class="px-6 py-4">
        <button class="font-medium text-blue-600 dark:text-blue-500 hover:underline edit-button"
            data-user-id="${user.id}">
            Edit
        </button>
        <button class="font-medium text-red-600 dark:text-red-500 ml-2 hover:underline delete-button"
            data-user-id="${user.id}">
            Delete
        </button>
    </td>
`;

                        tableBody.appendChild(row);
                    });
                })
                .catch(error => {
                    console.error('Error fetching users:', error);
                });
        });

        // Al hacer clic en el botón "Editar"
        document.addEventListener('click', function (event) {
            if (event.target.classList.contains('edit-button')) {
                const userId = event.target.dataset.userId;

                // Hacer una solicitud Ajax para obtener los detalles del usuario
                fetch(`/users/${userId}`)
                    .then(response => response.json())
                    .then(user => {
                        console.log(user)
                        // Mostrar el formulario de edición y cargar los datos del usuario
                        const editForm = document.querySelector('#edit-user-form');
                        editForm.innerHTML = `



<div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">

<div>
       <!-- Campos de formulario para editar el usuario -->
          <input type="hidden" name="user_id" value="${user.id}">
          <div class="grid gap-4 mb-4 grid-cols-2">
        <div class="col-span-2">
            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre</label>
            <input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-dark dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Nombre" required="" value="${user.name}">
        </div>
        <div class="col-span-2 sm:col-span-1">
            <label for="price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Correo</label>
            <input type="email" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-dark dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="$2999" required="" value="${user.email}">
        </div>

    </div>
    <button type="submit" class="bg-green-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
<svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
            Guardar
        </button>
        <button type="button" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mt-4" onclick="closeModal()">
            Cancelar
        </button>
    </div>
</div>
                    `;
                            // Mostrar el modal de edición
                            document.getElementById('edit-user-modal').classList.remove('hidden');
                        })
                        .catch(error => {
                            console.error('Error fetching user details:', error);
                        });
                }
            });

            function closeModal() {
                document.getElementById('edit-user-modal').classList.add('hidden');
            }

            // Escuchar el evento de click en el botón de cancelar
            document.addEventListener('click', function (event) {
                if (event.target.matches('.cancel-button')) { // Asegúrate de reemplazar '.cancel-button' con el selector correcto si cambias el atributo class del botón de cancelar
                    closeModal();
                }
            });
            // Al enviar el formulario de edición
            document.addEventListener('submit', function (event) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                if (event.target.id === 'edit-user-form') {
                    event.preventDefault();

                    const formData = new FormData(event.target);
                    const userId = formData.get('user_id');

                    fetch(`/users/${userId}`, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            name: formData.get('name'),
                            email: formData.get('email')
                        })
                    })
                    .then(response => response.json())
                    // Después de la actualización exitosa del usuario
                    .then(data => {
                        if (data.message) {
                            // Ocultar el modal de edición
                            document.getElementById('edit-user-modal').classList.add('hidden');

                            // Mostrar mensaje de éxito
                            const successMessage = document.getElementById('success-message');
                            successMessage.classList.remove('hidden');
                            setTimeout(() => successMessage.classList.add('hidden'), 3000);

                            // Actualizar la fila del usuario en la tabla
                            const userRow = document.querySelector(
                                `#users-table tbody tr[data-user-id="${userId}"]`);
                            if (userRow) {
                                // Actualizar el ID, nombre y correo electrónico
                                userRow.children[0].textContent =
                                    userId; // Asegúrate de que el índice sea correcto para el ID
                                userRow.children[2].textContent = formData.get('name'); // Actualizar el nombre
                                userRow.children[3].textContent = formData.get(
                                    'email'); // Actualizar el correo electrónico

                            } else {
                                console.error('User row not found');
                            }
                        } else {
                            throw new Error('Failed to update user');
                        }
                    })
                    .catch(error => {
                        console.error('Error updating user:', error);
                    });
                }
            });

            function deleteUser(userId) {
                // Mostrar mensaje de confirmación antes de eliminar
                if (confirm('¿Estás seguro de eliminar este usuario?')) {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    fetch(`/users/${userId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken, // Asegúrate de tener el token CSRF disponible en tu variable global
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Actualizar la tabla después de eliminar el usuario
                            const rows = document.querySelectorAll('#users-table tbody tr');
                            rows.forEach(row => {
                                if (row.getAttribute('data-user-id') == userId) {
                                    row.remove(); // Remover la fila correspondiente al usuario eliminado
                                }
                            });
                        } else {
                            console.error('Error deleting user:', data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error deleting user:', error);
                    });
                }
            }

            // Escuchar el evento de click en el botón de eliminar
            document.addEventListener('click', function (event) {
                if (event.target.matches('.delete-button')) {
                    const userId = event.target.dataset.userId;
                    deleteUser(userId);
                }
            });
        </script>
    </x-app-layout>
