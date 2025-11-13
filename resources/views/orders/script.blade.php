<script>
    /**
     * 🔹 Close Modal
     * NOTE: This function is exposed globally to be called from HTML onclick attributes.
     */
    function closeModal(id) {
        const modal = document.getElementById(id);
        const backdrop = document.getElementById('modalBackdrop');
        const body = document.body;

        if (!modal) return;

        // Animate out
        modal.children[0]?.classList.replace('opacity-100', 'opacity-0');
        modal.children[0]?.classList.replace('scale-100', 'scale-95');

        // Hide after animation (200ms)
        setTimeout(() => {
            modal.classList.add('hidden');
            backdrop?.classList.add('hidden');
            body.classList.remove('overflow-hidden');
        }, 300); // Increased delay slightly for better transition timing
    }


    document.addEventListener('DOMContentLoaded', () => {
        const body = document.body;
        const backdrop = document.getElementById('modalBackdrop');

        /**
         * 🔹 Open Modal (kept local, as it's only called internally by button listeners)
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
         */
        document.querySelectorAll('[data-modal]').forEach(btn => {
            btn.addEventListener('click', e => {
                e.preventDefault();
                const modalType = btn.dataset.modal; // view | edit | delete
                const orderId = btn.dataset.id;

                switch (modalType) {
                    case 'view':
                        // Placeholder data population
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

                        // case 'edit':
                        //     // Removed 'edit' placeholder logic since you didn't provide the modal,
                        //     // but left the openModal logic for 'delete'.
                        //     // If you add an edit modal, uncomment and adjust the logic below:
                        //     /*
                        //     document.getElementById('editForm').action = `/admin_dashboard/orders/${orderId}`;
                        //     openModal('editModal');
                        //     */
                        //     break;

                    case 'delete':
                        document.getElementById('deleteMessage').innerHTML =
                            `Are you sure you want to delete <strong>Order #${orderId}</strong>? This cannot be undone.`;
                        // Assuming your route is 'admin.destroyOrder'
                        document.getElementById('deleteForm').action =
                            `/admin_dashboard/orders/${orderId}`;
                        openModal('deleteModal');
                        break;
                }
            });
        });

        /**
         * 🔹 Attach all close buttons with [data-close-modal] attribute
         * (Though direct onclick is now the simpler path for close buttons)
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
