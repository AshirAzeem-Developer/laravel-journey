<div id="editModal" class="hidden fixed inset-0 flex items-center justify-center z-50 p-4 transition-all">
    <div
        class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-lg transform scale-95 opacity-0 transition-all duration-300">
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Edit Order</h3>
                <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" type="button"
                    onclick="closeModal('editModal')">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <div class="p-6 space-y-4">
                <div>
                    <label class="text-sm font-semibold text-gray-600 dark:text-gray-300">Order Number</label>
                    <input id="editOrderNumber" type="text" readonly
                        class="w-full mt-1 px-3 py-2 border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 rounded-lg text-gray-800 dark:text-gray-200" />
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-600 dark:text-gray-300">Total Amount</label>
                    <input id="editTotalAmount" name="total_amount" type="number" step="0.01"
                        class="w-full mt-1 px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-lg text-gray-800 dark:text-gray-200" />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-semibold text-gray-600 dark:text-gray-300">Payment Status</label>
                        <select id="editPaymentStatus" name="payment_status"
                            class="w-full mt-1 px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-lg text-gray-800 dark:text-gray-200">
                            <option value="pending">Pending</option>
                            <option value="paid">Paid</option>
                            <option value="refunded">Refunded</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-600 dark:text-gray-300">Order Status</label>
                        <select id="editOrderStatus" name="order_status"
                            class="w-full mt-1 px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-lg text-gray-800 dark:text-gray-200">
                            <option value="pending">Pending</option>
                            <option value="processing">Processing</option>
                            <option value="shipped">Shipped</option>
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-semibold text-gray-600 dark:text-gray-300">Payment Method</label>
                        <input id="editPaymentMethod" name="payment_method" type="text"
                            class="w-full mt-1 px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-lg text-gray-800 dark:text-gray-200" />
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-600 dark:text-gray-300">Transaction ID</label>
                        <input id="editTransactionId" name="transaction_id" type="text"
                            class="w-full mt-1 px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-lg text-gray-800 dark:text-gray-200" />
                    </div>
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-600 dark:text-gray-300">Shipping Address</label>
                    <textarea id="editShippingAddress" name="shipping_address" rows="3"
                        class="w-full mt-1 px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-lg text-gray-800 dark:text-gray-200"></textarea>
                </div>
            </div>

            <div class="p-4 border-t border-gray-200 dark:border-gray-700 flex justify-end">
                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-semibold">
                    <i class="fas fa-save mr-1"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
