<template>
  <div>
    <div 
      ref="newEmployeeModal"
      data-bs-backdrop="static"
      data-bs-keyboard="false"
      tabindex="-1"
      class="modal fade"
    >
      <div class="modal-dialog modal-fullscreen">
        <div class="modal-content text-bg-dark">
          <div class="modal-header border border-light border-opacity-25">
            <a class="navbar-brand d-flex align-items-center" href="#">
              <img  
                src="/img/nav.png"
                alt="Logo"
                width="38"
                height="38"
                class="rounded me-2"
              >
              <span class="d-none d-lg-inline">Xpert</span>
            </a>

            <h1 class="modal-title fs-4">Cadastrar Funcionário</h1>

            <button 
              data-bs-dismiss="modal"
              class="btn-close btn-close-white m-0"
              @click="resetEmployee"
            ></button>
          </div>

          <div class="modal-body bg-dark-blue-tint p-0">
            <div class="accordion accordion-flush accordion-arrow">
              <div class="accordion-item bg-dark-blue-tint border-0">
                <h2 class="accordion-header border border-light border-opacity-25">
                  <button
                    data-bs-toggle="collapse"
                    data-bs-target="#employee-collapse-1"
                    class="
                      accordion-button
                      fs-5
                      text-light
                      bg-light
                      bg-opacity-10
                      shadow-none
                    "
                  >
                    Dados do funcionário
                  </button>
                </h2>

                <div
                  id="employee-collapse-1"
                  class="accordion-collapse collapse show"
                >
                  <div class="container-lg vstack gap-3 py-3 px-0">
                    <span class="text-muted fs-6 ms-3">
                      <i class="fa-solid fa-star-of-life text-danger"></i>
                      = Obrigatório
                    </span>
                    <div class="bd-callout vstack gap-3 rounded">
                      <h3 class="text-light fs-6">Dados básicos</h3>
                      <div class="hstack flex-wrap gap-3">
                        <div 
                          class="hstack flex-wrap flex-fill gap-3"
                          style="width: 600px; min-width: 0;"
                        >
                          <div 
                            class="form-floating flex-fill"
                            style="width: 220px; min-width: 0;"
                          >
                            <input 
                              v-model="employee.name"
                              type="text"
                              placeholder="Nome"
                              class="form-control bg-light-smooth"
                            >
                            <label>
                              Nome
                              <i class="fa-solid fa-star-of-life text-danger text-opacity-75 ms-2"></i>
                            </label>
                          </div>

                          <div 
                            class="form-floating flex-fill"
                            style="width: 220px; min-width: 0;"
                          >
                            <input 
                              v-model="employee.surname"
                              type="text"
                              placeholder="Sobrenome"
                              class="form-control bg-light-smooth"
                            >
                            <label>
                              Sobrenome
                              <i class="fa-solid fa-star-of-life text-danger text-opacity-75 ms-2"></i>
                            </label>
                          </div>
                        </div>

                        <div 
                          class="hstack flex-wrap flex-fill gap-3"
                          style="width: 600px; min-width: 0;"
                        >
                          <div 
                            class="input-group flex-fill"
                            style="width: 220px; min-width: 0;"
                          >
                            <span class="input-group-text bg-light-grey">
                              Nascimento
                              <i class="fa-solid fa-star-of-life text-danger text-opacity-75 ms-2"></i>
                            </span>

                            <div 
                              class="form-floating flex-fill"
                              style="width: 50px; min-width: 0;"
                            >
                              <input 
                                v-model="employee.birth_d"
                                type="number"
                                placeholder="00"
                                class="form-control bg-light-smooth"
                              >
                              <label>Dia</label>
                            </div>

                            <div 
                              class="form-floating flex-fill"
                              style="width: 50px; min-width: 0;"
                            >
                              <input 
                                v-model="employee.birth_m"
                                type="number"
                                placeholder="00"
                                class="form-control bg-light-smooth"
                              >
                              <label>Mês</label>
                            </div>

                            <div 
                              class="form-floating flex-fill"
                              style="width: 220px; min-width: 0;"
                            >
                              <input 
                                v-model="employee.birth_y"
                                type="number"
                                placeholder="00"
                                class="form-control bg-light-smooth"
                              >
                              <label>Ano</label>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="hstack flex-wrap gap-3">
                        <div 
                          class="form-floating flex-fill"
                          style="width: 300px; min-width: 0;"
                        >
                          <input 
                            v-model="employee.cpf"
                            type="text"
                            placeholder="000.000.000-00"
                            class="form-control bg-light-smooth"
                          >
                          <label>CPF</label>
                        </div>

                        <div 
                          class="form-floating flex-fill"
                          style="width: 300px; min-width: 0;"
                        >
                          <select 
                            v-model="employee.job_type"
                            class="form-select bg-light-smooth"
                          >
                            <option 
                              v-for="option in system_types.job"
                              :key="option.value"
                              :value="option.value"
                            >
                              {{option.label}}
                            </option>
                          </select>
                          <label>
                            Cargo
                            <i class="fa-solid fa-star-of-life text-danger text-opacity-75 ms-2"></i>
                          </label>
                        </div>
                      </div>
                    </div>

                    <div class="hstack gap-3 flex-wrap align-items-stretch">
                      <div 
                        class="bd-callout vstack gap-3 flex-fill rounded"
                        style="width: 600px; min-width: 0;"
                      >
                        <h3 class="text-light fs-6">Endereço</h3>

                        <div class="hstack flex-wrap gap-3">
                          <div 
                            class="form-floating flex-fill"
                            style="width: 600px; min-width: 0;"
                          >
                            <input 
                              v-model="employee.address.line"
                              type="text"
                              placeholder="Logradouro"
                              class="form-control bg-light-smooth"
                            >
                            <label>
                              Logradouro
                              <i class="fa-solid fa-star-of-life text-danger text-opacity-75 ms-2"></i>
                            </label>
                          </div>

                          <div 
                            class="form-floating flex-fill"
                            style="width: 120px; min-width: 0;"
                          >
                            <input 
                              v-model="employee.address.number"
                              type="text"
                              placeholder="Número"
                              class="form-control bg-light-smooth"
                            >
                            <label>
                              Nº
                              <i class="fa-solid fa-star-of-life text-danger text-opacity-75 ms-2"></i>
                            </label>
                          </div>

                          <div 
                            class="form-floating flex-fill"
                            style="width: 220px; min-width: 0;"
                          >
                            <input 
                              v-model="employee.address.number_info"
                              type="text"
                              placeholder="Complemento do número"
                              class="form-control bg-light-smooth"
                            >
                            <label>Compl. Nº</label>
                          </div>

                          <div 
                            class="form-floating flex-fill"
                            style="width: 220px; min-width: 0;"
                          >
                            <input 
                              v-model="employee.address.district"
                              type="text"
                              placeholder="Cidade"
                              class="form-control bg-light-smooth"
                            >
                            <label>
                              Bairro
                              <i class="fa-solid fa-star-of-life text-danger text-opacity-75 ms-2"></i>
                            </label>
                          </div>

                          <div 
                            class="form-floating flex-fill"
                            style="width: 220px; min-width: 0;"
                          >
                            <input 
                              v-model="employee.address.city"
                              type="text"
                              placeholder="Cidade"
                              class="form-control bg-light-smooth"
                            >
                            <label>
                              Cidade
                              <i class="fa-solid fa-star-of-life text-danger text-opacity-75 ms-2"></i>
                            </label>
                          </div>

                          <div 
                            class="form-floating flex-fill"
                            style="width: 220px; min-width: 0;"
                          >
                            <select 
                              v-model="employee.address.uf"
                              type="text"
                              class="form-select bg-light-smooth"
                            >
                              <option
                                v-for="option in system_types.uf"
                                :key="option.value"
                                :value="option.value"
                              >{{option.label}}</option>
                            </select>
                            <label>UF<i class="fa-solid fa-star-of-life text-danger text-opacity-75 ms-2"></i></label>
                          </div>

                          <div 
                            class="form-floating flex-fill"
                            style="width:220px; min-width: 0;"
                          >
                            <input 
                              v-model="employee.address.cep"
                              type="text"
                              placeholder="CEP"
                              class="form-control bg-light-smooth"
                            >
                            <label>CEP</label>
                          </div>
                        </div>
                      </div>

                      <div class="bd-callout vstack gap-3 flex-fill rounded" style="width: 600px;">
                        <h3 class="text-light fs-6">Contato</h3>

                        <div class="hstack flex-wrap gap-3">
                          <div 
                            class="form-floating flex-fill"
                            style="width: 220px;"
                          >
                            <input 
                              v-model="employee.phone"
                              type="text"
                              placeholder="Telefone"
                              class="form-control bg-light-smooth"
                            >
                            <label>Telefone</label>
                          </div>

                          <div 
                            class="form-floating flex-fill"
                            style="width: 220px;"
                          >
                            <input 
                              v-model="employee.email"
                              type="text"
                              placeholder="E-mail"
                              class="form-control bg-light-smooth"
                            >
                            <label>E-mail</label>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="accordion-item bg-dark-blue-tint border-0">
                <h2 class="accordion-header border border-light border-opacity-25">
                  <button 
                    data-bs-toggle="collapse"
                    data-bs-target="#employee-collapse-2"
                    class="
                      accordion-button
                      fs-5
                      text-light
                      bg-light
                      bg-opacity-10
                      shadow-none
                    "
                  >
                    Dados de dependentes
                  </button>
                </h2>

                <div 
                  id="employee-collapse-2"
                  class="accordion-collapse collapse show"
                >
                  <div class="container-lg vstack gap-3 py-3 px-0">
                    <span class="text-muted fs-6 ms-3">
                      <i class="fa-solid fa-star-of-life text-danger"></i>
                      = Obrigatório
                    </span>

                    <div v-if="employee.family.length > 0" class="vstack gap-3">
                      <div 
                        v-for="(person, index) in employee.family"
                        :key="index"
                        class="bd-callout vstack gap-3 rounded"
                      >
                        <h3 class="hstack justify-content-between text-light fs-6">
                          Dependente {{index+1}}
                          <button class="btn btn-info btn-sm"
                            @click="removeFamily(index)"
                          >
                            <i class="fa-solid fa-user-minus me-1"></i>
                            Remover
                          </button>
                        </h3>

                        <div class="hstack flex-wrap gap-3">
                          <div 
                            class="hstack flex-wrap flex-fill gap-3"
                            style="width: 600px; min-width: 0;"
                          >
                            <div 
                              class="form-floating flex-fill"
                              style="width: 220px; min-width: 0;"
                            >
                              <input 
                                v-model="person.name"
                                type="text"
                                placeholder="Nome"
                                class="form-control bg-light-smooth"
                              >
                              <label>
                                Nome
                                <i class="fa-solid fa-star-of-life text-danger text-opacity-75 ms-2"></i>
                              </label>
                            </div>

                            <div 
                              class="form-floating flex-fill"
                              style="width: 220px; min-width: 0;"
                            >
                              <input 
                                v-model="person.surname"
                                type="text"
                                class="form-control bg-light-smooth"
                                placeholder="Sobrenome"
                              >
                              <label>
                                Sobrenome
                                <i class="fa-solid fa-star-of-life text-danger text-opacity-75 ms-2"></i>
                              </label>
                            </div>
                          </div>

                          <div 
                            class="hstack flex-wrap flex-fill gap-3"
                            style="width: 600px; min-width: 0;"
                          >
                            <div 
                              class="input-group flex-fill"
                              style="width: 220px; min-width: 0;"
                            >
                              <span class="input-group-text bg-light-grey">
                                Nascimento
                                <i class="fa-solid fa-star-of-life text-danger text-opacity-75 ms-2"></i>
                              </span>

                              <div 
                                class="form-floating flex-fill"
                                style="width: 50px; min-width: 0;"
                              >
                                <input 
                                  v-model="person.birth_d"
                                  type="number"
                                  placeholder="00"
                                  class="form-control bg-light-smooth"
                                >
                                <label>Dia</label>
                              </div>

                              <div 
                                class="form-floating flex-fill"
                                style="width: 50px; min-width: 0;"
                              >
                                <input 
                                  v-model="person.birth_m"
                                  type="number"
                                  placeholder="00"
                                  class="form-control bg-light-smooth"
                                >
                                <label>Mês</label>
                              </div>

                              <div 
                                class="form-floating flex-fill"
                                style="width: 220px; min-width: 0;"
                              >
                                <input 
                                  v-model="person.birth_y"
                                  type="number"
                                  placeholder="00"
                                  class="form-control bg-light-smooth"
                                >
                                <label>Ano</label>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="hstack flex-wrap gap-3">
                          <div 
                            class="form-floating flex-fill"
                            style="width: 300px; min-width: 0;"
                          >
                            <input 
                              v-model="person.cpf"
                              type="text"
                              placeholder="000.000.000-00"
                              class="form-control bg-light-smooth"
                            >
                            <label>CPF</label>
                          </div>

                          <div 
                            class="form-floating flex-fill"
                            style="width: 300px; min-width: 0;"
                          >
                            <select 
                              v-model="person.id_family_type"
                              class="form-select"
                            >
                              <option 
                                v-for="option in system_types.family"
                                :key="option.value"
                                :value="option.value"
                              >
                                {{option.label}}
                              </option>
                            </select>
                            <label>
                              Selecione um tipo
                              <i class="fa-solid fa-star-of-life text-danger text-opacity-75 ms-2"></i>
                            </label>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="bd-callout vstack gap-3 rounded">
                      <h3 class="hstack text-warning fs-6 m-0">
                        <i class="fa-solid fa-circle-info me-1"></i>
                        Adicione um dependente
                        <div class="ms-auto">
                          <button 
                            class="btn btn-warning btn-sm"
                            @click="addFamily"
                          >
                            <i class="fa-solid fa-user-plus me-1"></i>
                            Adicionar
                          </button>
                        </div>
                      </h3>
                    </div>
                  </div>
                </div>
              </div>

              <div class="accordion-item bg-dark-blue-tint border-0">
                <h2 class="accordion-header border border-light border-opacity-25">
                  <button 
                    data-bs-toggle="collapse"
                    data-bs-target="#employee-collapse-3"
                    class="
                      accordion-button
                      fs-5
                      text-light
                      bg-light
                      bg-opacity-10
                      shadow-none
                    "
                  >
                    Dados de usuário
                  </button>
                </h2>

                <div 
                  id="employee-collapse-3"
                  class="accordion-collapse collapse show"
                >
                  <div class="container-lg vstack gap-3 py-3 px-0">
                    <span class="text-muted fs-6 ms-3">
                      <i class="fa-solid fa-star-of-life text-danger"></i>
                      = Obrigatório
                    </span>

                    <div class="bd-callout vstack gap-3 rounded">
                      <h3 class="hstack text-warning fs-6 m-0">
                        <i class="fa-solid fa-circle-info me-1"></i>
                        Criar usuário
                        <div class="ms-auto">
                          <button 
                            v-if="!employee.user.create"
                            class="btn btn-warning btn-sm"
                            @click="employee.user.create = true"
                          >
                            <i class="fa-solid fa-user-plus me-1"></i>
                            Criar
                          </button>
                          <button 
                            v-if="employee.user.create"
                            class="btn btn-info btn-sm"
                            @click="employee.user.create = false"
                          >
                            <i class="fa-solid fa-user-xmark me-1"></i>
                            Cancelar
                          </button>
                        </div>
                      </h3>

                      <div v-if="employee.user.create" class="hstack flex-wrap gap-3">
                        <div 
                          class="input-group flex-fill"
                          style="width: 220px; min-width: 0;"
                        >
                          <div class="form-floating">
                            <input 
                              v-model="employee.user.user"
                              type="text"
                              placeholder="Telefone"
                              class="form-control bg-light-smooth"
                              :class="{
                                'is-invalid': (
                                  employee.user.create &&
                                  !user_availability.available &&
                                  user_availability.available != undefined
                                )
                              }"
                              :disabled="!employee.user.create"
                              @input="checkUserAvailability"
                            >
                            <label 
                              v-if="user_availability.available === false"
                              class="text-danger"
                            >
                              Usuário indisponível
                              <i class="fa-solid fa-star-of-life text-danger text-opacity-75 ms-2"></i>
                            </label>
                            <label v-if="user_availability.available != false">
                              Usuário
                              <i class="fa-solid fa-star-of-life text-danger text-opacity-75 ms-2"></i>
                            </label>
                          </div>

                          <span class="input-group-text bg-light-grey">
                            {{session_user.postfix}}
                          </span>
                        </div>

                        <div 
                          class="form-floating flex-fill"
                          style="width: 220px; min-width: 0;"
                        >
                          <input 
                            v-model="employee.user.pass"
                            type="password"
                            placeholder="E-mail"
                            class="form-control bg-light-smooth"
                            :disabled="!employee.user.create"
                          >
                          <label>
                            Senha
                            <i class="fa-solid fa-star-of-life text-danger text-opacity-75 ms-2"></i>
                          </label>
                        </div>

                        <div 
                          class="form-floating flex-fill"
                          style="width: 220px; min-width: 0;"
                        >
                          <select 
                            v-model="employee.user.id_user_group"
                            class="form-select"
                            :disabled="!employee.user.create"
                          >
                            <option 
                              v-for="option in system_types.user_group"
                              :key="option.value"
                              :value="option.value"
                            >
                              {{option.label}}
                            </option>
                          </select>
                          <label>
                            Selecione um grupo
                            <i class="fa-solid fa-star-of-life text-danger text-opacity-75 ms-2"></i>
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="modal-footer border border-light border-opacity-25">
            <button 
              data-bs-dismiss="modal"
              class="btn btn-info"
              @click="resetEmployee"
            >
              Cancelar
            </button>

            <button 
              class="btn btn-primary btn-orange"
              :disabled="(
                !is_employee_ready || (
                  employee.user.create &&
                  !is_user_ready
                ) || (
                  employee.family.length > 0 &&
                  !is_family_ready
                )
              )"
              @click="insertEmployee"
            >
              Cadastrar
            </button>
          </div>
        </div>
      </div>
    </div>


    <div 
      ref="newFamilyModal"
      data-bs-backdrop="static"
      data-bs-keyboard="false"
      tabindex="-1"
      class="modal fade"
    >
      <div class="modal-dialog modal-fullscreen">
        <div class="modal-content text-bg-dark">
          <div class="modal-header border border-light border-opacity-25">
            <a class="navbar-brand d-flex align-items-center" href="#">
              <img 
                src="/img/nav.png"
                alt="Logo"
                width="38"
                height="38"
                class="rounded me-2"
              >
              <span class="d-none d-lg-inline">Xpert</span>
            </a>

            <h1 class="modal-title fs-4">Cadastrar Dependente</h1>

            <button 
              class="btn-close btn-close-white m-0"
              data-bs-dismiss="modal"
              @click="resetFamily"
            ></button>
          </div>

          <div class="modal-body bg-dark-blue-tint p-0">
            <div 
              class="accordion accordion-flush accordion-arrow"
            >
              <div class="accordion-item bg-dark-blue-tint border-0">
                <h2 class="accordion-header border border-light border-opacity-25">
                  <button 
                    data-bs-toggle="collapse"
                    data-bs-target="#family-collapse-1"
                    class="
                      accordion-button
                      fs-5
                      text-light
                      bg-light
                      bg-opacity-10
                      shadow-none
                    "
                  >
                    Selecionar funcionário
                  </button>
                </h2>

                <div 
                  id="family-collapse-1"
                  class="accordion-collapse collapse show"
                >
                  <div class="container-lg vstack gap-3 py-3 px-0">
                    <span class="text-muted fs-6 ms-3">
                      <i class="fa-solid fa-circle-info text-warning"></i>
                      Somente funcionários com situação cadastral ativa aparecerão nos resultados
                    </span>
                    <div class="hstack flex-wrap gap-3">
                      <div class="input-group">
                        <div 
                          class="form-floating flex-fill"
                          style="width: 220px; min-width: 0;"
                        >
                          <input
                            v-model="employees_id_search.search"
                            type="text"
                            placeholder="Nome"
                            class="form-control bg-light-smooth"
                            @keyup.enter="getEmployeeId"
                          >
                          <label>
                            Nome
                            <i class="fa-solid fa-star-of-life text-danger"></i>
                          </label>
                        </div>
                        <div 
                          class="form-floating flex-fill"
                          style="width: 220px; min-width: 0;"
                        >
                          <select 
                            v-model="employees_id_search.job_type"
                            class="form-select bg-light-smooth"
                          >
                            <option :value="null">Qualquer cargo</option>
                            <option 
                              v-for="option in system_types.job"
                              :key="option.value"
                              :value="option.value"
                            >
                              {{option.label}}
                            </option>
                          </select>
                          <label>
                            Cargo
                          </label>
                        </div>
                        <button
                          class="btn btn-info"
                          @click="getEmployeeId"
                          :disabled="!employees_id_search.search"
                        >
                          <i class="fa-solid fa-magnifying-glass me-2"></i>
                          Pesquisar
                        </button>
                      </div>
                    </div>

                    <div 
                      class="table-responsive border border-light border-opacity-25 rounded"
                      style="max-height: 40vh;"
                    >
                      <table class="table table-dark table-hover m-0">
                        <thead class="sticky-top">
                          <tr>
                            <td>Funcionário</td>
                            <td>Cargo</td>
                            <td>Dependentes</td>
                          </tr>
                        </thead>
                        <tbody>
                          <tr
                            v-for="employee in employees_id_search.result "
                            :key="employee.id"
                            class="nav-pointer"
                            :class="{
                              'table-active': (employees_id_search.selected.id == employee.id)
                            }"
                            @click="selectEmployeeId(employee)"
                          >
                            <td>{{employee.name}}</td>
                            <td>{{employee.job}}</td>
                            <td>{{employee.family}}</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>

              <div class="accordion-item bg-dark-blue-tint border-0">
                <h2 class="accordion-header border border-light border-opacity-25">
                  <button 
                    data-bs-toggle="collapse"
                    data-bs-target="#family-collapse-2"
                    class="
                      accordion-button
                      fs-5
                      text-light
                      bg-light
                      bg-opacity-10
                      shadow-none
                    "
                  >
                    Dados de dependentes
                  </button>
                </h2>

                <div 
                  id="family-collapse-2"
                  class="accordion-collapse collapse show"
                >
                  <div class="container-lg vstack gap-3 py-3 px-0">
                    <span class="text-muted fs-6 ms-3">
                      <i class="fa-solid fa-star-of-life text-danger"></i>
                      = Obrigatório
                    </span>

                    <div v-if="employee.family.length > 0" class="vstack gap-3">
                      <div 
                        v-for="(person, index) in employee.family"
                        :key="index"
                        class="bd-callout vstack gap-3 rounded"
                      >
                        <h3 class="hstack justify-content-between text-light fs-6">
                          Dependente {{index+1}}
                          <button class="btn btn-info btn-sm"
                            @click="removeFamily(index)"
                          >
                            <i class="fa-solid fa-user-minus me-1"></i>
                            Remover
                          </button>
                        </h3>

                        <div class="hstack flex-wrap gap-3">
                          <div 
                            class="hstack flex-wrap flex-fill gap-3"
                            style="width: 600px; min-width: 0;"
                          >
                            <div 
                              class="form-floating flex-fill"
                              style="width: 220px; min-width: 0;"
                            >
                              <input 
                                v-model="person.name"
                                type="text"
                                placeholder="Nome"
                                class="form-control bg-light-smooth"
                              >
                              <label>
                                Nome
                                <i class="fa-solid fa-star-of-life text-danger text-opacity-75 ms-2"></i>
                              </label>
                            </div>

                            <div 
                              class="form-floating flex-fill"
                              style="width: 220px; min-width: 0;"
                            >
                              <input 
                                v-model="person.surname"
                                type="text"
                                class="form-control bg-light-smooth"
                                placeholder="Sobrenome"
                              >
                              <label>
                                Sobrenome
                                <i class="fa-solid fa-star-of-life text-danger text-opacity-75 ms-2"></i>
                              </label>
                            </div>
                          </div>

                          <div 
                            class="hstack flex-wrap flex-fill gap-3"
                            style="width: 600px; min-width: 0;"
                          >
                            <div 
                              class="input-group flex-fill"
                              style="width: 220px; min-width: 0;"
                            >
                              <span class="input-group-text bg-light-grey">
                                Nascimento
                                <i class="fa-solid fa-star-of-life text-danger text-opacity-75 ms-2"></i>
                              </span>

                              <div 
                                class="form-floating flex-fill"
                                style="width: 50px; min-width: 0;"
                              >
                                <input 
                                  v-model="person.birth_d"
                                  type="number"
                                  class="form-control bg-light-smooth"
                                  placeholder="00"
                                >
                                <label>Dia</label>
                              </div>

                              <div 
                                class="form-floating flex-fill"
                                style="width: 50px; min-width: 0;"
                              >
                                <input 
                                  v-model="person.birth_m"
                                  type="number"
                                  class="form-control bg-light-smooth"
                                  placeholder="00"
                                >
                                <label>Mês</label>
                              </div>

                              <div 
                                class="form-floating flex-fill"
                                style="width: 220px; min-width: 0;"
                              >
                                <input 
                                  v-model="person.birth_y"
                                  type="number"
                                  class="form-control bg-light-smooth"
                                  placeholder="00"
                                >
                                <label>Ano</label>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="hstack flex-wrap gap-3">
                          <div 
                            class="form-floating flex-fill"
                            style="width: 300px; min-width: 0;"
                          >
                            <input 
                              v-model="person.cpf"
                              type="text"
                              class="form-control bg-light-smooth"
                              placeholder="000.000.000-00"
                            >
                            <label>CPF</label>
                          </div>

                          <div 
                            class="form-floating flex-fill"
                            style="width: 300px; min-width: 0;"
                          >
                            <select 
                              v-model="person.id_family_type"
                              class="form-select"
                            >
                              <option 
                                v-for="option in system_types.family"
                                :key="option.value"
                                :value="option.value"
                              >
                                {{option.label}}
                              </option>
                            </select>
                            <label>
                              Selecione um tipo
                              <i class="fa-solid fa-star-of-life text-danger text-opacity-75 ms-2"></i>
                            </label>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div 
                      v-if="employees_id_search.selected.id === null"
                      class="bd-callout vstack gap-3 rounded"
                    >
                      <h3 class="hstack text-danger fs-6 m-0">
                        <i class="fa-solid fa-circle-info me-1"></i>
                        Primeiro selecione um funcionário
                      </h3>
                    </div>

                    <div 
                      v-if="employees_id_search.selected.id != null"
                      class="bd-callout vstack gap-3 rounded"
                    >
                      <h3 class="hstack text-warning fs-6 m-0">
                        <i class="fa-solid fa-circle-info me-1"></i>
                        Adicione um dependente para: <b class="ms-1">{{employees_id_search.selected.name}}</b>
                        <div class="ms-auto">
                          <button 
                            class="btn btn-warning btn-sm"
                            @click="addFamily"
                            :disabled="(employees_id_search.selected.id === null)"
                          >
                            <i class="fa-solid fa-user-plus me-1"></i>
                            Adicionar
                          </button>
                        </div>
                      </h3>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="modal-footer border border-light border-opacity-25">
            <button 
              class="btn btn-info"
              data-bs-dismiss="modal"
              @click="resetFamily"
            >
              Cancelar
            </button>

            <button 
              class="btn btn-primary btn-orange"
              :disabled="(!is_family_ready)"
              @click="insertFamily"
            >
              Cadastrar
            </button>
          </div>
        </div>
      </div>
    </div>


    <div 
      ref="newUserModal"
      data-bs-backdrop="static"
      data-bs-keyboard="false"
      tabindex="-1"
      class="modal fade"
    >
      <div class="modal-dialog modal-fullscreen">
        <div class="modal-content text-bg-dark">
          <div class="modal-header border border-light border-opacity-25">
            <a class="navbar-brand d-flex align-items-center" href="#">
              <img 
                src="/img/nav.png"
                alt="Logo"
                width="38"
                height="38"
                class="rounded me-2"
              >
              <span class="d-none d-lg-inline">Xpert</span>
            </a>

            <h1 class="modal-title fs-4">Cadastrar Usuário</h1>

            <button 
              data-bs-dismiss="modal"
              class="btn-close btn-close-white m-0"
              @click="resetUser"
            ></button>
          </div>

          <div class="modal-body bg-dark-blue-tint p-0">
            <div 
              class="accordion accordion-flush accordion-arrow"
            >
              <div class="accordion-item bg-dark-blue-tint border-0">
                <h2 class="accordion-header border border-light border-opacity-25">
                  <button 
                    data-bs-toggle="collapse"
                    data-bs-target="#user-collapse-1"
                    class="
                      accordion-button
                      fs-5
                      text-light
                      bg-light
                      bg-opacity-10
                      shadow-none
                    " 
                  >
                    Selecionar funcionário
                  </button>
                </h2>

                <div 
                  id="user-collapse-1"
                  class="accordion-collapse collapse show"
                >
                  <div class="container-lg vstack gap-3 py-3 px-0">
                    <span class="text-muted fs-6 ms-3">
                      <i class="fa-solid fa-circle-info text-warning"></i>
                      Somente funcionários com situação cadastral ativa aparecerão nos resultados
                    </span>
                    <span class="text-muted fs-6 ms-3">
                      <i class="fa-solid fa-circle-info text-warning"></i>
                      Somente funcionários sem cadastro de usuário podem ser selecionados
                    </span>
                    <div class="hstack flex-wrap gap-3">
                      <div class="input-group">
                        <div 
                          class="form-floating flex-fill"
                          style="width: 220px; min-width: 0;"
                        >
                          <input 
                            v-model="employees_id_search.search"
                            type="text"
                            placeholder="Nome"
                            class="form-control bg-light-smooth"
                            @keyup.enter="getEmployeeId"
                          >
                          <label>
                            Nome
                            <i class="fa-solid fa-star-of-life text-danger"></i>
                          </label>
                        </div>
                        <div 
                          class="form-floating flex-fill"
                          style="width: 220px; min-width: 0;"
                        >
                          <select 
                            v-model="employees_id_search.job_type"
                            class="form-select bg-light-smooth"
                          >
                            <option :value="null">Qualquer cargo</option>
                            <option 
                              v-for="option in system_types.job"
                              :key="option.value"
                              :value="option.value"
                            >
                              {{option.label}}
                            </option>
                          </select>
                          <label>
                            Cargo
                          </label>
                        </div>
                        <button 
                          class="btn btn-info"
                          @click="getEmployeeId"
                          :disabled="!employees_id_search.search"
                        >
                          <i class="fa-solid fa-magnifying-glass me-2"></i>
                          Pesquisar
                        </button>
                      </div>
                    </div>

                    <div 
                      class="table-responsive border border-light border-opacity-25 rounded"
                      style="max-height: 40vh;"
                    >
                      <table class="table table-dark table-hover m-0">
                        <thead class="sticky-top">
                          <tr>
                            <td>Funcionário</td>
                            <td>Cargo</td>
                            <td>Possui usuário</td>
                          </tr>
                        </thead>
                        <tbody>
                          <tr
                            v-for="employee in employees_id_search.result "
                            :key="employee.id"
                            class="nav-pointer"
                            :class="{
                              'table-active': (employees_id_search.selected.id == employee.id),
                              'text-muted no-click': (employee.is_user === 'true')
                            }"
                            @click="selectEmployeeId(employee)"
                          >
                            <td>{{employee.name}}</td>
                            <td>{{employee.job}}</td>
                            <td>{{employee.is_user === 'true'? 'Sim': 'Não'}}</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>

              <div class="accordion-item bg-dark-blue-tint border-0">
                <h2 class="accordion-header border border-light border-opacity-25">
                  <button 
                    data-bs-toggle="collapse"
                    data-bs-target="#user-collapse-2"
                    class="
                      accordion-button
                      fs-5
                      text-light
                      bg-light
                      bg-opacity-10
                      shadow-none
                    "
                  >
                    Dados de usuário
                  </button>
                </h2>
                <div 
                  id="user-collapse-2"
                  class="accordion-collapse collapse show"
                >
                  <div class="container-lg vstack gap-3 py-3 px-0">
                    <span class="text-muted fs-6 ms-3">
                      <i class="fa-solid fa-star-of-life text-danger"></i>
                      = Obrigatório
                    </span>

                    <div class="bd-callout vstack gap-3 rounded">
                      <h3 
                        v-if="employees_id_search.selected.id === null"
                        class="text-danger fs-6 m-0"
                      >
                        <i class="fa-solid fa-circle-info"></i>
                        Primeiro selecione um funcionário
                      </h3>
                      <h3 
                        v-if="employees_id_search.selected.id"
                        class="text-warning fs-6 m-0"
                      >
                        <i class="fa-solid fa-circle-info"></i>
                        Criar usuário para: <b>{{employees_id_search.selected.name}}</b>
                      </h3>

                      <div v-if="employee.user.create" class="hstack flex-wrap gap-3">
                        <div class="input-group flex-fill" style="width: 220px; min-width: 0;">
                          <div class="form-floating">
                            <input 
                              v-model="employee.user.user"
                              type="text"
                              placeholder="Telefone"
                              class="form-control bg-light-smooth"
                              :class="{
                                'is-invalid': (
                                  !user_availability.available &&
                                  user_availability.available != undefined
                                )
                              }"
                              :disabled="employees_id_search.selected.id === null"
                              @input="checkUserAvailability"
                            >
                            <label 
                              v-if="user_availability.available === false"
                              class="text-danger"
                            >
                              Usuário indisponível
                              <i class="fa-solid fa-star-of-life text-danger text-opacity-75 ms-2"></i>
                            </label>
                            <label v-if="user_availability.available != false">
                              Usuário
                              <i class="fa-solid fa-star-of-life text-danger text-opacity-75 ms-2"></i>
                            </label>
                          </div>

                          <span class="input-group-text bg-light-grey">{{session_user.postfix}}</span>
                        </div>

                        <div 
                          class="form-floating flex-fill"
                          style="width: 220px; min-width: 0;"
                        >
                          <input 
                            v-model="employee.user.pass"
                            type="password"
                            placeholder="E-mail"
                            class="form-control bg-light-smooth"
                            :disabled="employees_id_search.selected.id === null"
                          >
                          <label>
                            Senha
                            <i class="fa-solid fa-star-of-life text-danger text-opacity-75 ms-2"></i>
                          </label>
                        </div>

                        <div 
                          class="form-floating flex-fill"
                          style="width: 220px; min-width: 0;"
                        >
                          <select 
                            v-model="employee.user.id_user_group"
                            class="form-select"
                            :disabled="employees_id_search.selected.id === null"
                          >
                            <option 
                              v-for="option in system_types.user_group"
                              :key="option.value"
                              :value="option.value"
                            >
                              {{option.label}}
                            </option>
                          </select>
                          <label>
                            Selecione um grupo
                            <i class="fa-solid fa-star-of-life text-danger text-opacity-75 ms-2"></i>
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="modal-footer border border-light border-opacity-25">
            <button 
              data-bs-dismiss="modal"
              class="btn btn-info"
              @click="resetUser"
            >
              Cancelar
            </button>
            <button 
              class="btn btn-primary btn-orange"
              :disabled="!is_user_ready"
              @click="insertUser"
            >
              Cadastrar
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
  export default {
    components: {},

    directives: {},

    props: [
      'session_user',
      'system_types'
    ],

    data(){
      return {
        employees_id_search: {
          search:   null,
          job_type: null,
          selected: { id: null },
          result:   [],
        },
        employee: {
          person_type: 1,
          job_type:    null,
          id_entity:   null,
          name:        null,
          surname:     null,
          birth_d:     null,
          birth_m:     null,
          birth_y:     null,
          cpf:         null,
          phone:       null,
          email:       null,
          address: {
            address_type: 1,
            line:         null,
            number:       null,
            number_info:  null,
            district:     null,
            city:         null,
            uf:           null,
            cep:          null,
          },
          family: [],
          user: {
            create:        false,
            id_user_group: null,
            user:          null,
            postfix:       null,
            pass:          null,
          },
        },
        user_availability: {
          available: undefined,
          timer:     undefined,
        },
      }
    },

    computed: {
      is_employee_ready(){
        if (
          !this.employee.job_type         ||
          !this.employee.name             ||
          !this.employee.surname          ||
          !this.employee.birth_d          ||
          !this.employee.birth_m          ||
          !this.employee.birth_y          ||
          !this.employee.address.line     ||
          !this.employee.address.number   ||
          !this.employee.address.district ||
          !this.employee.address.city     ||
          !this.employee.address.uf
        ){ return false; } else { return true; }
      },

      is_family_ready(){
        if(this.employee.family.length == 0){ return undefined; }
        if(this.employee.family.length > 0){
          var ready;
          for( let i = 0; i < this.employee.family.length; i++ ){
            if(
              !this.employee.family[i].id_family_type ||
              !this.employee.family[i].name           ||
              !this.employee.family[i].surname        ||
              !this.employee.family[i].birth_d        ||
              !this.employee.family[i].birth_m        ||
              !this.employee.family[i].birth_y
            ){
              ready = false;
              break;
            }
          }
          if(ready === false){ return false; } else { return true; }
        }
      },

      is_user_ready(){
        if(!this.employee.user.create){ return undefined; }
        if(this.employee.user.create && !this.user_availability.available){ return false; }
        if(
          this.employee.user.create && (
            !this.employee.user.id_user_group                     ||
            !this.employee.user.pass                              ||
            !this.employee.user.user                              ||
            this.employee.user.user == this.employee.user.postfix ||
            this.employee.user.user.includes('@')
          )
        ){ return false; }
        return true;
      },

      user_create(){ return this.employee.user.create; },
    },

    watch: {
      user_create(){
        if (!this.employee.user.create){
          this.user_availability.available = undefined;
          this.employee.user.id_user_group = null;
          this.employee.user.user          = null;
          this.employee.user.pass          = null;
        }
      },

      session_user(){
        this.employee.id_entity    = this.session_user.id_entity;
        this.employee.user.postfix = this.session_user.postfix;
      }
    },

    created(){
      this.employee.id_entity    = this.session_user.id_entity;
      this.employee.user.postfix = this.session_user.postfix;
    },

    mounted(){},

    updated(){},

    activated(){},

    deactivated(){},

    methods: {
      openNewEmployeeModal(){
        bootstrap.Modal.getOrCreateInstance(this.$refs.newEmployeeModal).show();
      },

      openNewFamilyModal(){
        bootstrap.Modal.getOrCreateInstance(this.$refs.newFamilyModal).show();
      },

      openNewUserModal(){
        bootstrap.Modal.getOrCreateInstance(this.$refs.newUserModal).show();
      },

      insertEmployee(){
        this.$emit('isLoading', true);
        api.insertEmployee(this.employee)
          .then((response)=>{
            if(response.data.success){
              bootstrap.Modal.getOrCreateInstance(this.$refs.newEmployeeModal).hide();
              this.resetEmployee();
              this.$emit('showAlert', 'success', 'Funcionário cadastrado com sucesso.');
            }
          })
          .catch((error)=>{
            console.log(error);
          })
          .finally(()=>{
            this.$emit('isLoading', false);
          });
      },

      insertFamily(){
        this.$emit('isLoading', true);
        api.insertFamily(
          this.employee.id_entity,
          this.employees_id_search.selected.id,
          this.employee.family
        )
          .then((response)=>{
            if(response.data.success){
              bootstrap.Modal.getOrCreateInstance(this.$refs.newFamilyModal).hide();
              this.resetFamily();
              this.$emit('showAlert', 'success', 'Dependente cadastrado com sucesso.');
            }
          })
          .catch((error)=>{
            console.log(error);
          })
          .finally(()=>{
            this.$emit('isLoading', false);
          });
      },

      insertUser(){
        this.$emit('isLoading', true);
        api.insertUser(this.employees_id_search.selected.id, this.employee.user)
          .then((response)=>{
            if(response.data.success){
              bootstrap.Modal.getOrCreateInstance(this.$refs.newUserModal).hide();
              this.resetUser();
              this.$emit('showAlert', 'success', 'Usuário cadastrado com sucesso.');
            }
          })
          .catch((error)=>{
            console.log(error);
          })
          .finally(()=>{
            this.$emit('isLoading', false);
          });
      },

      getEmployeeId(){
        if(!this.employees_id_search.search){
          this.$emit('showAlert', 'warning', 'O campo de pesquisa "Nome" deve ser preenchido.');
          return;
        }
        this.$emit('isLoading', true);
        api.listEmployees(
          this.employees_id_search.search,
          this.employees_id_search.job_type,
          1,   // active
          0,   // page
          1000 // page_limit
        )
          .then((response)=>{
            if(response.data.success){
              this.employees_id_search.result = response.data.result;
            } else {
              switch(response.data.error){
                case 'sql':
                  this.$emit('showAlert', 'error', 'SQL inválido, informe o suporte.');
                  break;
                case 'empty':
                  this.$emit('showAlert', 'warning', 'Nenhum resultado encontrado.');
                  break;
              }
            }
          })
          .catch((error)=>{
            console.log(error);
          })
          .finally(()=>{
            this.$emit('isLoading', false);
          });
      },

      selectEmployeeId(employee){
        this.employees_id_search.selected = employee;
        this.employee.user.create         = true;
      },

      addFamily(){
        this.employee.family.push({
          id_family_type: null,
          name:           null,
          surname:        null,
          cpf:            null,
          birth_d:        null,
          birth_m:        null,
          birth_y:        null,
        });
      },

      removeFamily(index){
        this.employee.family.splice(index, 1);
      },

      checkUserAvailability(){
        clearTimeout(this.user_availability.timer);
        this.user_availability.timer = setTimeout(() => {
          api.isUserAvailable(`${this.employee.user.user}${this.employee.user.postfix}`)
            .then((response)=>{
              this.user_availability.available = response.data.available;
            })
            .catch((error)=>{
              console.log(error);
            })
        }, 600);
      },

      resetFamily(){
        this.employee.family     = [];
        this.employees_id_search = {
          search:   null,
          job_type: null,
          selected: { id: null },
          result:   [],
        };
      },

      resetUser(){
        this.employee.user = {
          create:        false,
          id_user_group: null,
          user:          null,
          postfix:       this.session_user.postfix,
          pass:          null,
        };
        this.user_availability = {
          available: undefined,
          timer:     undefined,
        };
        this.employees_id_search = {
          search:   null,
          job_type: null,
          selected: { id: null },
          result:   [],
        };
      },

      resetEmployee(){
        this.employee={
          person_type: 1,
          job_type:    null,
          id_entity:   this.session_user.id_entity,
          name:        null,
          surname:     null,
          birth_d:     null,
          birth_m:     null,
          birth_y:     null,
          cpf:         null,
          phone:       null,
          email:       null,
          address: {
            address_type: 1,
            line:         null,
            number:       null,
            number_info:  null,
            district:     null,
            city:         null,
            uf:           null,
            cep:          null,
          },
          family: [],
          user: {
            create:        false,
            id_user_group: null,
            user:          null,
            postfix:       this.session_user.postfix,
            pass:          null,
          },
        };
      },
    },
  }
</script>