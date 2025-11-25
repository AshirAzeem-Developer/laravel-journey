<script>
    /**
     * 🔹 Close Modal (Exposed globally for HTML onclick)
     */
    function closeModal(id) {
        const modal = document.getElementById(id);
        const backdrop = document.getElementById('modalBackdrop');
        const body = document.body;

        if (!modal) return;

        // Animate out
        modal.children[0]?.classList.replace('opacity-100', 'opacity-0');
        modal.children[0]?.classList.replace('scale-100', 'scale-95');

        // Hide after animation (300ms)
        setTimeout(() => {
            modal.classList.add('hidden');
            backdrop?.classList.add('hidden');
            body.classList.remove('overflow-hidden');
        }, 300);
    }

    document.addEventListener('DOMContentLoaded', () => {
        const body = document.body;
        const backdrop = document.getElementById('modalBackdrop');
        const viewContent = document.getElementById('viewContent');

        /**
         * 🔹 Open Modal (Local function)
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
         * 🔹 Fetch Order Details and Build View Modal Content
         */
        document.querySelectorAll('[data-modal]').forEach(btn => {
            btn.addEventListener('click', async (e) => {
                e.preventDefault();
                const modalType = btn.dataset.modal; // view | delete
                const orderId = btn.dataset.id;

                if (modalType === 'delete') {
                    // --- DELETE Logic ---
                    document.getElementById('deleteMessage').innerHTML =
                        `Are you sure you want to delete <strong>Order #${orderId}</strong>? This cannot be undone.`;
                    // NOTE: Adjust route if your naming convention is different
                    document.getElementById('deleteForm').action =
                        `/admin_dashboard/orders/${orderId}`;
                    openModal('deleteModal');
                    return;
                }

                if (modalType === 'view') {
                    // --- VIEW Logic: Show loading state ---
                    viewContent.innerHTML = `
                        <div class="text-center py-8">
                            <i class="fas fa-spinner fa-spin text-indigo-500 text-2xl"></i>
                            <p class="text-sm mt-2">Loading order details...</p>
                        </div>
                    `;
                    openModal('viewModal');

                    try {
                        // 1. AJAX call to fetch actual order data
                        // NOTE: You must have a backend route configured for this URL
                        const response = await fetch(`Orders/${orderId}`, {
                            headers: {

                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        });
                        if (!response.ok) {
                            throw new Error('Failed to fetch order details.');
                        }
                        const order = await response.json();
                        console.log(order);

                        // Format Date
                        const createdAt = new Date(order.created_at);
                        const dateString = createdAt.toLocaleDateString('en-US', {
                            month: 'short',
                            day: 'numeric',
                            year: 'numeric'
                        });
                        const timeString = createdAt.toLocaleTimeString('en-US', {
                            hour: 'numeric',
                            minute: '2-digit',
                            hour12: true
                        });

                        // 2. Build dynamic HTML content with actual data
                        viewContent.innerHTML = `
                            <h4 class="text-lg font-bold border-b border-gray-200 dark:border-gray-700 pb-2 mb-4 text-indigo-600 dark:text-indigo-400">Order Information</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div><strong>Order #:</strong> ${order.order_number}</div>
                                <div><strong>Amount:</strong> $${(order.total_amount)}</div>
                                <div><strong>Date:</strong> ${dateString} - ${timeString}</div>
                                <div><strong>Customer:</strong> ${order?.user?.name ? order.user.name : 'Guest User'} (ID: ${order.user_id || 'N/A'})</div>
                            </div>

                            <h4 class="text-lg font-bold border-b border-gray-200 dark:border-gray-700 pb-2 mb-4 mt-6 text-indigo-600 dark:text-indigo-400">Status & Payment</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div><strong>Payment Status:</strong> <span class="capitalize font-semibold text-green-600">${order.payment_status}</span></div>
                                <div><strong>Order Status:</strong> <span class="capitalize font-semibold text-blue-600">${order.order_status}</span></div>
                                <div><strong>Transaction ID:</strong> ${order.transaction_id || 'N/A'}</div>
                                <div><strong>Payment Method:</strong> ${order.payment_method}</div>
                            </div>

                            <h4 class="text-lg font-bold border-b border-gray-200 dark:border-gray-700 pb-2 mb-4 mt-6 text-indigo-600 dark:text-indigo-400">Shipping Details</h4>
                            <div class="col-span-2">
                                <p class="text-sm">${order.shipping_address}</p>
                            </div>
                        `;

                    } catch (error) {
                        console.error('Error fetching order details:', error);
                        viewContent.innerHTML = `
                            <div class="text-center py-8 text-red-600 dark:text-red-400">
                                <i class="fas fa-exclamation-triangle text-2xl"></i>
                                <p class="text-sm mt-2">Could not load order details. Please try again.</p>
                            </div>
                        `;
                    }
                }
            });
        });

        // Event listener for data-close-modal buttons (optional, as onclick is now global)
        document.querySelectorAll('[data-close-modal]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const target = btn.dataset.closeModal;
                if (target) closeModal(target);
            });
        });
    });
</script>
