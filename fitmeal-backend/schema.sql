const API_BASE = 'http://localhost/fitmeal/fitmeal-backend';

// Récupérer les plans
async function getPlans() {
    try {
        const response = await fetch(`${API_BASE}/plans/list.php`);
        const data = await response.json();
        console.log(data);
        return data;
    } catch (error) {
        console.error('Erreur:', error);
    }
}

// Créer un plan
async function createPlan(planData) {
    try {
        const response = await fetch(`${API_BASE}/plans/create.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(planData)
        });
        return await response.json();
    } catch (error) {
        console.error('Erreur:', error);
    }
}