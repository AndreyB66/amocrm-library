<?php

namespace Integrat\Amocrm\Repositories;

use Integrat\Amocrm\Models\CompanyModel;
use Integrat\Amocrm\Models\ContactModel;
use Integrat\Amocrm\Models\LeadModel;

class LeadRepository extends AbstractRepository
{
    public function create(array $data): int
    {
        $result = $this->request->post('/leads', $data);
        
        if (empty($result['_embedded']['leads'][0]['id'])) {
            throw new \Exception(
                'Не удалось создать сделку. Входящие данные: ' . print_r($data, true) . 'Ответ: ' . print_r($result, true)
            );
        }

        return $result['_embedded']['leads'][0]['id'];
    }

    public function update(array $data): int
    {
        $result = $this->request->patch('/leads', $data);

        if (empty($result['_embedded']['leads'][0]['id'])) {
            throw new \Exception(
                'Не удалось обновить сделку. Входящие данные: ' . print_r($data, true) . 'Ответ: ' . print_r($result, true)
            );
        }

        return $result['_embedded']['leads'][0]['id'];
    }

    /**
     * Получить сделку по ID
     * @param int $leadId
     * @return LeadModel|null
     */
    public function findById(int $leadId): ?LeadModel
    {
        $result = $this->request->get('/leads/' . $leadId . '?with=contacts,companies');
        return empty($result) ? null : new LeadModel($result);
    }

    /**
     * Найти сделку/сделки по полю
     * @param string $fieldValue
     * @return LeadModel[]
     */
    public function findByField(string $fieldValue): array
    {
        $result = $this->request->get('/leads?with=contacts,companies&query=' . urlencode($fieldValue));
        
        if (empty($result['_embedded']['leads'])) {
            return [];
        }

        return array_map(
            fn($lead) => new LeadModel($lead),
            $result['_embedded']['leads']
        );
    }

    /**
     * Получить все связанные контакты
     * @param int $leadId
     * @return ContactModel[]
     */
    public function findAllContacts(int $leadId): array
    {
        return $this->findRelatedEntities($leadId, 'leads', 'contacts', ContactModel::class);
    }

    public function findFirstContact(int $leadId): ?ContactModel
    {
        return $this->findFirstRelatedEntity($leadId, 'leads', 'contacts', ContactModel::class);
    }

    public function findCompany(int $leadId): ?CompanyModel
    {
        return $this->findFirstRelatedEntity($leadId, 'leads', 'companies', CompanyModel::class);
    }

    /**
     * Получить массив сделок за 1 запрос
     * @param array $leadIds
     * @return LeadModel[]
     */
    public function findBulk(array $leadIds): array
    {
        return $this->loadEntitiesByIds('leads', $leadIds, LeadModel::class);
    }
}