/**
 * Admin Dashboard JavaScript
 * Author: Ahmed Argoubi
 * Portfolio Admin Panel
 */

// ===== COUNTER ANIMATION =====
const animateCounter = () => {
    const counters = document.querySelectorAll('.stat-number');
    
    counters.forEach(counter => {
        const target = +counter.getAttribute('data-count');
        const duration = 2000; // 2 seconds
        const increment = target / (duration / 16); // 60 FPS
        let current = 0;
        
        const updateCounter = () => {
            if (current < target) {
                current += increment;
                counter.textContent = Math.ceil(current);
                requestAnimationFrame(updateCounter);
            } else {
                counter.textContent = target;
            }
        };
        
        updateCounter();
    });
};

// ===== CURRENT TIME UPDATE =====
const updateTime = () => {
    const now = new Date();
    const options = {
        weekday: 'short',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    };
    const timeString = now.toLocaleString('en-US', options);
    const timeElement = document.getElementById('currentTime');
    
    if (timeElement) {
        timeElement.textContent = timeString;
    }
};

// ===== PROJECTS CHART =====
const initProjectsChart = () => {
    const ctx = document.getElementById('projectsChart');
    if (!ctx) return;
    
    // Gradient colors
    const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(207, 9, 6, 0.8)');
    gradient.addColorStop(1, 'rgba(207, 9, 6, 0.2)');
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June'],
            datasets: [{
                label: 'Projects Created',
                data: [3, 5, 2, 8, 4, 6],
                backgroundColor: gradient,
                borderColor: '#cf0906',
                borderWidth: 2,
                borderRadius: 10,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        color: '#a0a4b8',
                        font: {
                            size: 13,
                            weight: '600'
                        },
                        padding: 20
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(26, 26, 46, 0.95)',
                    titleColor: '#ffffff',
                    bodyColor: '#a0a4b8',
                    borderColor: '#cf0906',
                    borderWidth: 1,
                    padding: 12,
                    displayColors: true,
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y + ' projects';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#a0a4b8',
                        font: {
                            size: 12
                        },
                        stepSize: 2
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.05)',
                        drawBorder: false
                    }
                },
                x: {
                    ticks: {
                        color: '#a0a4b8',
                        font: {
                            size: 12
                        }
                    },
                    grid: {
                        display: false,
                        drawBorder: false
                    }
                }
            },
            animation: {
                duration: 1500,
                easing: 'easeInOutQuart'
            }
        }
    });
};

// ===== MESSAGES CHART =====
const initMessagesChart = () => {
    const ctx = document.getElementById('messagesChart');
    if (!ctx) return;
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Read', 'Unread', 'Replied'],
            datasets: [{
                data: [45, 25, 30],
                backgroundColor: [
                    'rgba(0, 200, 83, 0.8)',
                    'rgba(207, 9, 6, 0.8)',
                    'rgba(0, 176, 255, 0.8)'
                ],
                borderColor: [
                    '#00c853',
                    '#cf0906',
                    '#00b0ff'
                ],
                borderWidth: 2,
                hoverOffset: 15
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: '#a0a4b8',
                        padding: 15,
                        font: {
                            size: 12,
                            weight: '600'
                        },
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(26, 26, 46, 0.95)',
                    titleColor: '#ffffff',
                    bodyColor: '#a0a4b8',
                    borderColor: '#cf0906',
                    borderWidth: 1,
                    padding: 12,
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return label + ': ' + value + ' (' + percentage + '%)';
                        }
                    }
                }
            },
            animation: {
                animateRotate: true,
                animateScale: true,
                duration: 1500,
                easing: 'easeInOutQuart'
            }
        }
    });
};

// ===== ANIMATE PROGRESS BARS =====
const animateProgressBars = () => {
    const progressBars = document.querySelectorAll('.stat-progress-bar');
    
    progressBars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0%';
        bar.style.opacity = '0';
        
        setTimeout(() => {
            bar.style.transition = 'all 1.5s ease-out';
            bar.style.width = width;
            bar.style.opacity = '1';
        }, 300);
    });
};

// ===== SMOOTH SCROLL FOR INTERNAL LINKS =====
const initSmoothScroll = () => {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
};

// ===== TOOLTIP INITIALIZATION =====
const initTooltips = () => {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
};

// ===== ADD HOVER EFFECTS TO CARDS =====
const initCardEffects = () => {
    const cards = document.querySelectorAll('.stat-card, .action-card, .admin-card');
    
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transition = 'transform 0.3s ease';
        });
    });
};

// ===== NOTIFICATION SYSTEM =====
const showNotification = (message, type = 'info') => {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 100px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 5000);
};

// ===== AUTO REFRESH STATS (Optional) =====
const autoRefreshStats = () => {
    // Uncomment this if you want to auto-refresh stats every 5 minutes
    // setInterval(() => {
    //     location.reload();
    // }, 300000); // 5 minutes
};

// ===== KEYBOARD SHORTCUTS =====
const initKeyboardShortcuts = () => {
    document.addEventListener('keydown', (e) => {
        // Ctrl/Cmd + K to focus search (if you add search later)
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            // Focus search input
            const searchInput = document.querySelector('#searchInput');
            if (searchInput) {
                searchInput.focus();
            }
        }
        
        // Ctrl/Cmd + N to create new project
        if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
            e.preventDefault();
            window.location.href = '/admin/project/new';
        }
    });
};

// ===== INITIALIZE EVERYTHING ON PAGE LOAD =====
document.addEventListener('DOMContentLoaded', () => {
    console.log('🚀 Admin Dashboard Initialized');
    
    // Update time immediately and then every minute
    updateTime();
    setInterval(updateTime, 60000);
    
    // Animate counters with delay
    setTimeout(() => {
        animateCounter();
    }, 500);
    
    // Animate progress bars
    setTimeout(() => {
        animateProgressBars();
    }, 1000);
    
    // Initialize charts
    setTimeout(() => {
        initProjectsChart();
        initMessagesChart();
    }, 800);
    
    // Initialize other features
    initSmoothScroll();
    initCardEffects();
    initKeyboardShortcuts();
    
    // Initialize tooltips if Bootstrap is loaded
    if (typeof bootstrap !== 'undefined') {
        initTooltips();
    }
    
    // Optional: Auto refresh
    // autoRefreshStats();
    
    // Add page loaded class for animations
    document.body.classList.add('page-loaded');
    
    console.log('✅ All dashboard features loaded successfully');
});

// ===== HANDLE PAGE VISIBILITY =====
document.addEventListener('visibilitychange', () => {
    if (document.hidden) {
        console.log('Dashboard hidden');
    } else {
        console.log('Dashboard visible - updating time');
        updateTime();
    }
});

// ===== EXPORT FUNCTIONS (if needed) =====
window.dashboardFunctions = {
    animateCounter,
    updateTime,
    showNotification,
    initProjectsChart,
    initMessagesChart
};

// ===== CONSOLE GREETING =====
console.log('%c Ahmed Argoubi Portfolio Admin Dashboard ', 'background: #cf0906; color: #fff; font-size: 16px; padding: 10px;');
console.log('%c Security Engineer | Cybersecurity Specialist ', 'background: #1a1a2e; color: #00d9ff; font-size: 12px; padding: 5px;');