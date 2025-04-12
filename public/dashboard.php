
<?php
session_start();
require_once __DIR__ . '/../includes/db_connect.php';
require_once __DIR__ . '/../includes/auth_functions.php';
require_once __DIR__ . '/../includes/medicine_functions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Invalid CSRF token');
    }
    
    if (isset($_POST['add_medicine'])) {
        $name = htmlspecialchars($_POST['name']);
        $startDate = $_POST['start_date'];
        $endDate = $_POST['end_date'];
        $time = $_POST['time'];
        $dosage = htmlspecialchars($_POST['dosage']);
        
        addMedicine($_SESSION['user_id'], $name, $startDate, $endDate, $time, $dosage);
    }
    
    header("Location: dashboard.php");
    exit;
}

$medicines = getMedicines($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedCare - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-50 min-h-screen" style="font-family: 'Poppins', sans-serif;">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 bg-white rounded-xl p-6 shadow-lg">
            <div class="flex items-center mb-4 md:mb-0">
                <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCIgd2lkdGg9IjI0IiBoZWlnaHQ9IjI0IiBmaWxsPSIjMzg5OWZmIj48cGF0aCBkPSJNMTIgMmMtMy45NyAwLTcuMTkgMS4yMy05LjY2IDMuOEwzIDUuMjVsMy40NiAzLjQ3YzEuOTctMS40NCA0LjQzLTIuMzIgNy4wOC0yLjQ4IDIuNjUuMTYgNS4xMSAxLjA0IDcuMDggMi40OEwyMSA1LjI1bC0xLjM0LTEuMzZDMTcuMTkgMy4yMyAxMy45NyAyIDEyIDJ6bTAgMmMzLjA3IDAgNS45NCAxLjE0IDguMSAzIC4xNi4xNC4xNy4zOC4wNC41NWwtMi40NCAyLjQ1LTMuMDUtMi4yOGMtMS42OS0xLjI2LTMuOC0yLjEyLTYuMTUtMi4yMS0yLjM1LjA5LTQuNDYuOTUtNi4xNSAyLjIxbC0zLjA1IDIuMjgtMi40NC0yLjQ1Yy0uMTMtLjE3LS4xMi0uNDEuMDQtLjU1QzYuMDYgNS4xNCA4LjkzIDQgMTIgNHptLS4wMSA0Yy0xLjA1IDAtMiAuODgtMiAxLjk1IDAgMS4wNC44OSAxLjkgMiAxLjloMWMxIDAgMS44Ni43OSAxLjk2IDEuNzhsLjA0LjIyYy4wNC4yLjI0LjM2LjQzLjI4bC4yLS4wOGMuMi0uMS4yOC0uMzMuMTgtLjUyLS4zNC0uNjYtMS4wMS0xLjExLTEuNzktMS4xMWgtMWMtLjU1IDAtMS0uNDEtMS0uOTVTMTEuNDQgOCAxMiA4aDEuMWMuOSAwIDEuNzIuNTcgMiAxaC45YzEuMSAwIDIgLjkgMiAycy0uOSAyLTIgMmgtMWMtLjU1IDAtMSAuNDUtMSAxcy40NSAxIDEgMWgxYzEuNjYgMCAzLTEuMzQgMy0zIDAtMS42LTEuMzYtMy0zLTNoLTEuMWMtLjI4IDAtLjUtLjIyLS41LS41cy4yMi0uNS41LS41aDEuMWMxLjEgMCAyIC45IDIgMiAwIDEuMDYtLjkgMi0yIDJIMTNjLS41NSAwLTEtLjQ1LTEtMXMuNDUtMSAxLTFoMWMxLjEgMCAyLS45IDItMnMtLjktMi0yLTJoLTEuMWMtMS4xIDAtMiAuOS0yIDIgMCAuNDUuMTUuODUuMzggMS4xNmwtMS41NCAxLjUzYy0uMzEtLjE3LS42Ni0uMjktMS4wNC0uMzNMMTMgOS41VjE4YzAgLjU1LS40NSAxLTEgMXMtMS0uNDUtMS0xdi0xLjVoLTJ2MS41YzAgLjU1LS40NSAxLTEgMXMtMS0uNDUtMS0xdi0zaC0xYy0uNTUgMC0xLS40NS0xLTFzLjQ1LTEgMS0xaDN2LTFoMWMuNTUgMCAxLS40NSAxLTFzLS40NS0xLTEtMWgtMXYtMWMwLS41NS0uNDUtMS0xLTF6bS0zLjkgOGMtLjI4IDAtLjUtLjIyLS41LS41cy4yMi0uNS41LS41aDEuMWMuMjggMCAuNS4yMi41LjVzLS4yMi41LS41LjVIOC4wOXoiLz48L3N2Zz4=" class="h-12 w-12 mr-3">
                <div>
                    <h1 class="text-3xl font-bold text-blue-600">MedCare</h1>
                    <p class="text-sm text-gray-500">Your Personal Health Companion</p>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <div class="text-right">
                    <p class="text-sm text-gray-600">Reminders sent to</p>
                    <p class="font-medium text-blue-600"><?= $_SESSION['email'] ?? 'your@email.com' ?></p>
                </div>
                <a href="logout.php" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-xl transition-all duration-300 transform hover:scale-105 flex items-center">
                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                </a>
            </div>
        </div>

        <!-- Stats Widgets -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="bg-blue-100 p-3 rounded-lg mr-4">
                        <i class="fas fa-pills text-blue-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Total Medicines</p>
                        <p class="text-2xl font-bold"><?= count($medicines) ?></p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="bg-green-100 p-3 rounded-lg mr-4">
                        <i class="fas fa-clock text-green-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Upcoming Doses</p>
                        <p class="text-2xl font-bold" id="upcomingCount">0</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="bg-purple-100 p-3 rounded-lg mr-4">
                        <i class="fas fa-check-circle text-purple-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Completed Today</p>
                        <p class="text-2xl font-bold">0</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Medicine Form -->
        <div class="bg-white rounded-xl shadow-lg mb-8 p-6 hover:shadow-xl transition-shadow duration-300">
            <form method="post">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-gray-600 text-sm font-medium mb-2">Medicine Name</label>
                        <input type="text" name="name" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    </div>
                    <div>
                        <label class="block text-gray-600 text-sm font-medium mb-2">Dosage</label>
                        <input type="text" name="dosage" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    </div>
                    <div>
                        <label class="block text-gray-600 text-sm font-medium mb-2">Start Date</label>
                        <input type="date" name="start_date" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    </div>
                    <div>
                        <label class="block text-gray-600 text-sm font-medium mb-2">End Date</label>
                        <input type="date" name="end_date" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    </div>
                    <div>
                        <label class="block text-gray-600 text-sm font-medium mb-2">Time</label>
                        <input type="time" name="time" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    </div>
                </div>
                
                <button type="submit" name="add_medicine" class="mt-6 bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-8 py-3 rounded-lg transition-all duration-300 transform hover:scale-105 w-full font-medium">
                    <i class="fas fa-plus-circle mr-2"></i>Add Medicine
                </button>
            </form>
        </div>

        <!-- Medicine List -->
        <div id="medicineList" class="space-y-4">
            <?php if (count($medicines) > 0): ?>
                <?php foreach ($medicines as $medicine): 
                    $now = new DateTime();
                    $medTime = DateTime::createFromFormat('H:i:s', $medicine['time']);
                    $today = new DateTime();
                    $today->setTime($medTime->format('H'), $medTime->format('i'));
                    
                    if($today < $now) {
                        $today->modify('+1 day');
                    }
                    
                    $interval = $now->diff($today);
                    $hoursLeft = $interval->h;
                    $minutesLeft = $interval->i;
                    
                    $startDate = new DateTime($medicine['start_date']);
                    $endDate = new DateTime($medicine['end_date']);
                    $daysLeft = $endDate->diff($startDate)->days;
                ?>
                <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 medicine-card">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center mb-4">
                                <div class="bg-blue-100 p-2 rounded-lg mr-4">
                                    <i class="fas fa-capsules text-blue-600 text-2xl"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800"><?= htmlspecialchars($medicine['name']) ?></h3>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Dosage</p>
                                    <p class="font-medium text-blue-600"><?= htmlspecialchars($medicine['dosage']) ?></p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Scheduled Time</p>
                                    <p class="font-medium text-purple-600">
                                        <i class="fas fa-clock mr-2"></i>
                                        <?= date('h:i A', strtotime($medicine['time'])) ?>
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Next Dose In</p>
                                    <div class="font-medium text-green-600" data-time="<?= $today->format('Y-m-d H:i:s') ?>">
                                        <i class="fas fa-hourglass-half mr-2"></i>
                                        <span class="hours"><?= $hoursLeft ?></span>h 
                                        <span class="minutes"><?= $minutesLeft ?></span>m
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Remaining Doses</p>
                                    <p class="font-medium text-red-600">
                                        <i class="fas fa-calendar-alt mr-2"></i>
                                        <?= $daysLeft ?> days
                                    </p>
                                </div>
                            </div>
                        </div>
                        <button class="text-red-500 hover:text-red-700 delete-btn ml-4 self-start" data-id="<?= $medicine['id'] ?>">
                            <i class="fas fa-trash-alt text-xl"></i>
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="bg-white p-8 text-center rounded-xl shadow-lg">
                    <div class="text-gray-400 text-6xl mb-4">
                        <i class="fas fa-prescription-bottle-alt"></i>
                    </div>
                    <h3 class="text-xl text-gray-500 mb-2">No medicines added yet</h3>
                    <p class="text-gray-400">Start by adding your first medication above</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="../assets/js/script.js"></script>
    <script>
        // Timer update function
        function updateTimers() {
            document.querySelectorAll('[data-time]').forEach(element => {
                const targetTime = new Date(element.dataset.time);
                const now = new Date();
                
                if(now > targetTime) {
                    targetTime.setDate(targetTime.getDate() + 1);
                    element.dataset.time = targetTime.toISOString();
                }
                
                const diff = targetTime - now;
                const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                
                element.querySelector('.hours').textContent = hours.toString().padStart(2, '0');
                element.querySelector('.minutes').textContent = minutes.toString().padStart(2, '0');
            });
        }

        // Initial update and set interval
        updateTimers();
        setInterval(updateTimers, 1000 * 60); // Update every minute

        // Update upcoming count
        const upcomingCount = document.getElementById('upcomingCount');
        function updateUpcomingCount() {
            const now = new Date();
            const count = Array.from(document.querySelectorAll('[data-time]')).filter(el => {
                const targetTime = new Date(el.dataset.time);
                return targetTime > now;
            }).length;
            upcomingCount.textContent = count;
        }
        updateUpcomingCount();
        setInterval(updateUpcomingCount, 1000 * 60);
    </script>
    
</body>
</html> 
