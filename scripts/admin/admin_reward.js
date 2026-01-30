const REWARDContainer = document.getElementById('reward-container');
let currentFilter = 'ALL';
let rewardsCache = [];

// --- 1. Utilities ---
const getIsActive = (val) => val == 1 || val === true || val === 'true' || val === '1';

// --- 2. HTML Generation ---
function createCardHTML(reward) {
    const stockCount = parseInt(reward.remaining) || 0;
    const isMarkedActive = getIsActive(reward.active);
    
    // A reward is only "Truly Active" if marked active AND has stock
    const isActuallyActive = isMarkedActive && stockCount > 0;

    const statusLabel = isActuallyActive ? 'ACTIVE' : (stockCount <= 0 ? 'OUT OF STOCK' : 'ENDED');
    const statusClass = isActuallyActive ? 'active' : 'ended';
    const btnText = isActuallyActive ? 'Discontinue' : 'Reactivate';

    return `
        <div class="card" data-id="${reward.ID}">                
            <div class="product-title-row">
                <div>
                    <h2>${reward.title}</h2>
                    <div class="tags">
                        <span class="type-tag">${reward.category || 'Eco'}</span>
                        <span class="pts-tag">${reward.price} pts</span>
                    </div>
                </div>
                <div class="badges">
                    <span class="badge ${statusClass}">${statusLabel}</span>
                </div>
            </div>

            <div class="image-container ${stockCount <= 0 ? 'out-of-stock-style' : ''}">
                <img src="${reward.media}" alt="${reward.title}">
            </div>

            <div class="description">
                <span class="descrip">${reward.description || 'No description available'}</span>
            </div>

            <div class="controls">
                <div class="control-row">
                    <span>Stock: ${stockCount}</span>
                    <div class="stock-count"> 
                        <span class="edit-icon" style="cursor:pointer">✎</span>
                    </div>
                </div>
            </div>

            <button class="state-btn ${statusClass}" ${stockCount <= 0 && !isMarkedActive ? 'disabled' : ''}>
                ${btnText}
            </button>
        </div>`;
}

// --- 3. Core Logic: Rendering & Filtering ---
function renderUI() {
    if (!REWARDContainer) return;

    const filtered = rewardsCache.filter(r => {
        const stockCount = parseInt(r.remaining) || 0;
        let isMarkedActive = getIsActive(r.active);

        // AUTO-DISCONTINUE LOGIC
        // If it's active in the cache but has no stock, kill it in the DB
        if (isMarkedActive && stockCount <= 0) {
            updateDatabase(r.ID, { active: 0 }); 
            r.active = 0; // Sync local cache
            isMarkedActive = false;
        }

        if (currentFilter === 'LOW_STOCK') return isMarkedActive && stockCount > 0 && stockCount < 10;
        if (currentFilter === 'ACTIVE') return isMarkedActive && stockCount > 0;
        if (currentFilter === 'ENDED') return !isMarkedActive || stockCount <= 0;
        return true; // ALL
    });

    REWARDContainer.innerHTML = filtered.length 
        ? filtered.map(createCardHTML).join('') 
        : `<p class="no-data">No rewards found for this category.</p>`;
}

// --- 4. Event Handlers ---
REWARDContainer.addEventListener('click', async (e) => {
    const card = e.target.closest('.card');
    if (!card) return;

    const rewardId = card.dataset.id;
    const item = rewardsCache.find(r => r.ID == rewardId);

    // Handle Edit Icon
    if (e.target.classList.contains('edit-icon')) {
        openEditPopup(rewardId);
    } 
    
    // Handle Toggle Button
    else if (e.target.classList.contains('state-btn')) {
        const currentActive = getIsActive(item.active);
        const nextState = currentActive ? 0 : 1; 

        if (await updateDatabase(rewardId, { active: nextState })) {
            item.active = nextState;
            renderUI();
        }
    }
});

function changeFilter(status, event) {
    document.querySelectorAll('#statistics span, .active-ended span').forEach(card => {
        card.classList.remove('active-filter');
    });

    if (event && event.currentTarget) {
        event.currentTarget.classList.add('active-filter');
    }

    currentFilter = status.toUpperCase() === 'ALL' ? 'ALL' : 
                    status.toUpperCase() === 'LOW' ? 'LOW_STOCK' : 
                    status.toUpperCase();
    renderUI();
}

// --- 5. Popups ---
function openEditPopup(id) {
    const item = rewardsCache.find(r => r.ID == id);
    if (!item) return;

    const form = document.querySelector('.reward-form');
    document.querySelector('#popup-header h3').innerText = "Edit Reward";
    form.querySelector('.submit-btn').textContent = "Confirm Changes";

    form.querySelector('[name="title"]').value = item.title;
    form.querySelector('[name="category"]').value = item.category;
    form.querySelector('[name="description"]').value = item.description || '';
    form.querySelector('[name="points"]').value = item.price;
    form.querySelector('[name="stock"]').value = item.remaining;
    
    let idInput = form.querySelector('[name="reward_id"]');
    if (!idInput) {
        idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = 'reward_id';
        form.appendChild(idInput);
    }
    idInput.value = item.ID;
    form.querySelector('[name="reward_image"]').required = false;

    document.getElementById("overlay").classList.add("active");
    document.getElementById("parent").classList.add("blur");
}

function closePopup() {
    document.getElementById("overlay").classList.remove("active");
    document.getElementById("parent").classList.remove("blur"); 
}

// --- 6. Data Fetching ---
async function fetchAndRender() {
    try {
        const response = await fetch('api/reward/fetch_all.php');
        const data = await response.json();
        rewardsCache = Object.values(data);
        renderUI();
    } catch (error) {
        console.error("Critical: Could not load rewards.", error);
    }
}

async function updateDatabase(id, data) {
    const params = new URLSearchParams({ id, ...data });
    try {
        const response = await fetch(`api/reward/update.php?${params.toString()}`);
        return response.ok; 
    } catch (error) {
        console.error("Database update failed.", error);
        return false;
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    const totalTab = document.getElementById('total-reward');
    if (totalTab) setTimeout(() => changeFilter('all', { currentTarget: totalTab }), 100);
});

fetchAndRender();