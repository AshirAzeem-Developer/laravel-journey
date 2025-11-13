<script>
    document.addEventListener('DOMContentLoaded', () => {
        const body = document.body;
        const backdrop = document.getElementById('modalBackdrop');

        /**
         * 🔹 Open Modal
         */
        function openModal(id) {
            const modal = document.getElementById(id);
            if (!modal) return;

            modal.classList.remove('hidden');
            backdrop?.classList.remove('hidden');
            body.classList.add('overflow-hidden');

            requestAnimationFrame(() => {
                modal.children[0]?.classList.replace('scale-95', 'scale-100');
                modal.children[0]?.classList.replace('opacity-0', 'opacity-100');
            });
        }

        /**
         * 🔹 Close Modal
         */
        function closeModal(id) {
            const modal = document.getElementById(id);
            if (!modal) return;

            modal.children[0]?.classList.replace('opacity-100', 'opacity-0');
            modal.children[0]?.classList.replace('scale-100', 'scale-95');

            setTimeout(() => {
                modal.classList.add('hidden');
                backdrop?.classList.add('hidden');
                body.classList.remove('overflow-hidden');
            }, 200);
        }

        /**
         * 🔹 Global backdrop + ESC close
         */
        backdrop?.addEventListener('click', () => {
            document.querySelectorAll('[id$="Modal"]').forEach(modal => {
                if (!modal.classList.contains('hidden')) closeModal(modal.id);
            });
        });

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                document.querySelectorAll('[id$="Modal"]').forEach(modal => {
                    if (!modal.classList.contains('hidden')) closeModal(modal.id);
                });
            }
        });

        /**
         * 🔹 Attach all modal open buttons dynamically
         *  Use data attributes in your buttons:
         *  e.g. <button data-modal="view" data-id="1024">View</button>
         */
        document.querySelectorAll('[data-modal]').forEach(btn => {
            btn.addEventListener('click', e => {
                e.preventDefault();
                const modalType = btn.dataset.modal; // view | edit | delete
                const orderId = btn.dataset.id;

                switch (modalType) {
                    case 'view':
                        document.getElementById('viewContent').innerHTML = `
                        <div class="grid grid-cols-2 gap-4">
                            <div><strong>Order #:</strong> ${orderId}</div>
                            <div><strong>Status:</strong> <span class="text-green-600">Delivered</span></div>
                            <div><strong>Amount:</strong> $250.00</div>
                            <div><strong>Payment:</strong> Paid via PayPal</div>
                            <div><strong>Customer:</strong> John Doe</div>
                            <div><strong>Date:</strong> Nov 12, 2025 - 02:15 PM</div>
                            <div class="col-span-2"><strong>Address:</strong> 123 Main Street, Karachi, Pakistan</div>
                        </div>`;
                        openModal('viewModal');
                        break;

                    case 'edit':
                        document.getElementById('editOrderNumber').value = orderId;
                        document.getElementById('editTotalAmount').value = '250';
                        document.getElementById('editPaymentStatus').value = 'paid';
                        document.getElementById('editOrderStatus').value = 'delivered';
                        document.getElementById('editPaymentMethod').value = 'paypal';
                        document.getElementById('editTransactionId').value = 'TXN12345';
                        document.getElementById('editShippingAddress').value =
                            '123 Main Street, Karachi, Pakistan';
                        document.getElementById('editForm').action =
                            `/admin_dashboard/orders/${orderId}`;
                        openModal('editModal');
                        break;

                    case 'delete':
                        document.getElementById('deleteMessage').innerHTML =
                            `Are you sure you want to delete <strong>Order #${orderId}</strong>? This cannot be undone.`;
                        document.getElementById('deleteForm').action =
                            `/admin_dashboard/orders/${orderId}`;
                        openModal('deleteModal');
                        break;
                }
            });
        });

        /**
         * 🔹 Attach all close buttons with [data-close-modal] attribute
         */
        document.querySelectorAll('[data-close-modal]').forEach(btn => {
            btn.addEventListener('click', e => {
                e.preventDefault();
                const target = btn.dataset.closeModal;
                if (target) closeModal(target);
            });
        });
    });
</script>
