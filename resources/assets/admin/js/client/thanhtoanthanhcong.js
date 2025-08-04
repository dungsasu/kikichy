$(document).ready(function () {
    clearCart();
})

function clearCart() {
    $.ajax({
        url: '/clear-cart',
        method: 'GET',
        data: { _token: $('meta[name="csrf-token"]').attr('content') },
        success: function(response) {
            // Handle success response
            // console.log('Cart cleared successfully');
        },
        error: function(xhr, status, error) {
            // Handle error response
            console.log('Error clearing cart:', error);
        }
    });
}
