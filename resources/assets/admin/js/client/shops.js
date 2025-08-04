$(document).ready(function () {
    // Attach click event listener to each .branch_location_desc_item
    $(".branch_location_desc_item").on("click", function () {
        var itemId = $(this).data("id"); // Get the ID of the clicked item

        // Make AJAX request to fetch map data
        $.ajax({
            url: "/get-map-data",
            type: "POST",
            data: {
                id: itemId,
            },
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"), // For Laravel CSRF token
            },
            success: function (response) {
                // Update the map with the received data
                updateMap(response.description);
            },
            error: function (xhr) {
                console.error("Error fetching map data:", xhr);
            },
        });
    });

    // Function to update the map
    function updateMap(description) {
        // Assuming you have a function to handle the map update
        // For example:
        $(".gg_maps_wrapper").html(description);
    }
    var firstItemId = $(".branch_location_desc_item").first().data("id"); // Lấy ID của phần tử đầu tiên
    if (firstItemId) {
        $.ajax({
            url: "/get-map-data",
            type: "POST",
            data: {
                id: firstItemId,
            },
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"), // CSRF token cho Laravel
            },
            success: function (response) {
                if (response.description) {
                    // Cập nhật bản đồ với dữ liệu nhận được
                    updateMap(response.description);
                } else {
                    console.error("Lỗi: ", response.error);
                }
            },
            error: function (xhr) {
                console.error("Lỗi khi lấy dữ liệu:", xhr);
            },
        });
    }
});
