<%
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.1.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
%>

    /**
     * Delete method
     *
     * @param string|null $id <%= $singularHumanName %> id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $<%= $singularName %> = $this-><%= $currentModelName %>->get($id);
        if ($this-><%= $currentModelName; %>->delete($<%= $singularName %>)) {
            $this->Flash->success(__d('backend','The {0} has been deleted.', __d('backend','<%= strtolower($singularHumanName) %>')));
        } else {
            $this->Flash->error(__d('backend','The {0} could not be deleted. Please, try again.', __d('backend','<%= strtolower($singularHumanName) %>')));
        }
        return $this->redirect(['action' => 'index']);
    }
