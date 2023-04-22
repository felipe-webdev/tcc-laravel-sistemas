<template>
  <div>
    <div
      ref="profileModal"
      data-bs-backdrop="static"
      data-bs-keyboard="false"
      tabindex="-1"
      class="modal fade"
    >
      <div class="modal-dialog modal-fullscreen">
        <div class="modal-content text-bg-dark">
          <div class="modal-header border border-light border-opacity-25 py-1">
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

            <h1 class="modal-title fs-3">Perfil do Funcionário</h1>

            <button
              data-bs-dismiss="modal"
              class="btn-close btn-close-white m-0"
              @click="resetProfile"
            ></button>
          </div>


          <div class="modal-body bg-dark-blue-tint p-0">
            <div
              ref="croppImageModal"
              id="staticBackdrop"
              data-bs-backdrop="static"
              data-bs-keyboard="false"
              tabindex="-1"
              class="modal fade"
            >
              <div class="modal-dialog modal-fullscreen">
                <div class="modal-content bg-dark text-light">
                  <div class="modal-header border border-info border-opacity-25 py-1">
                    <a class="navbar-brand d-flex align-items-center" href="#">
                      <img
                        src="/img/nav.png"
                        alt="Logo"
                        width="38"
                        height="38"
                        class="rounded me-2"
                      >
                      Xpert
                    </a>
                    <h1 class="modal-title fs-3" id="staticBackdropLabel">Definir imagem para {{`${backup.employee == undefined? 'Funcionário': backup.employee.name} ${backup.employee == undefined? '': backup.employee.surname}`}}</h1>
                    <button
                      class="btn-close btn-close-white m-0"
                      data-bs-dismiss="modal"
                      @click="closeCroppImageModal"
                    ></button>
                  </div>
                  <div class="modal-body vstack p-0">
                    <div class="bd-callout bd-callout-info">
                      <cropper v-if="editing.image" @blob="insertImage" />
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="container-lg hstack gap-4 p-4">
              <img
                :src="imageSrc"
                class="rounded-circle border border-2 border-light border-opacity-25 shadow-plus"
                style="width: 200px; height:200px"
              >
              <div class="vstack justify-content-center gap-2">
                <span class="fs-1">
                  {{`${backup.employee == undefined? 'Funcionário': backup.employee.name} ${backup.employee == undefined? '': backup.employee.surname}`}}
                </span>
                <span class="fs-4 text-muted">
                  {{`${backup.employee == undefined? 'Departamento': backup.employee.job_depart} / ${backup.employee == undefined? 'Cargo': backup.employee.job_type}`}}
                </span>
                <span v-if="backup.employee" class="fs-4 text-muted">
                  Situação do Funcionário:
                  <button
                    ref="setActive"
                    data-bs-toggle="dropdown"
                    data-bs-auto-close="outside"
                    class="btn btn-sm dropdown-toggle"
                    :class="{
                      'btn-success': backup.employee.active==1,
                      'btn-danger': backup.employee.active==0
                    }"
                  >
                    {{backup.employee.active==1? 'Ativo': 'Inativo'}}
                  </button>
                  <ul
                    class="dropdown-menu dropdown-menu-dark border border-light border-opacity-25 shadow-plus"
                  >
                    <li v-if="backup.employee.active==0">
                      <span class="dropdown-item-text text-warning fw-normal">
                        Alterar situação cadastral para Ativo.
                      </span>
                    </li>
                    <li v-if="backup.employee.active==1">
                      <span class="dropdown-item-text text-warning fw-normal">
                        Alterar situação cadastral para Inativo.
                      </span>
                    </li>
                    <li><span class="dropdown-item-text fw-normal">Deseja continuar?</span></li>
                    <li><hr class="dropdown-divider"></li>
                    <li class="hstack justify-content-end gap-2 px-3">
                      <button
                        class="btn btn-info btn-sm"
                        @click="$refs.setActive.click();"
                      >
                        <i class="fa-solid fa-xmark me-1"></i>
                        Não
                      </button>
                      <button
                        class="btn btn-orange btn-sm"
                        @click="$refs.setActive.click(); updateEmployee('active', {'active': employee.active == 1? 0: 1, 'id_employee': employee.id_employee})"
                      >
                        <i class="fa-solid fa-check me-1"></i>
                        Sim
                      </button>
                    </li>
                  </ul>
                </span>
                <span>
                  <button
                    class="btn btn-warning btn-sm"
                    @click="openCroppImageModal"
                  >
                    <i class="fa-solid fa-circle-user me-1"></i>
                    Imagem
                  </button>
                </span>
              </div>
            </div>


            <div class="nav nav-tabs justify-content-center border-light border-opacity-25">
              <button
                ref="infoTab"
                class="nav-link active"
                data-bs-toggle="tab"
                data-bs-target="#nav-info"
                type="button"
              >
                Funcionário
              </button>

              <button
                ref="contactsTab"
                class="nav-link"
                data-bs-toggle="tab"
                data-bs-target="#nav-contacts"
                type="button"
              >
                Contatos
              </button>

              <button
                ref="familyTab"
                class="nav-link"
                data-bs-toggle="tab"
                data-bs-target="#nav-family"
                type="button"
              >
                Dependentes
              </button>

              <button
                ref="userTab"
                class="nav-link"
                data-bs-toggle="tab"
                data-bs-target="#nav-user"
                type="button"
              >
                Usuário
              </button>
            </div>


            <div class="tab-content">
              <div class="tab-pane fade show active" id="nav-info">
                <div class="container-lg vstack flex-wrap gap-4 py-4">
                  <div class="hstack flex-wrap align-items-stretch gap-3">
                    <div class="bd-callout rounded vstack flex-fill gap-3" style="width: 600px; min-width: 0;">
                      <h3 class="hstack text-light fs-6">
                        Dados Pessoais
                        <div v-if="!editing.address && !editing.job" class="ms-auto">
                          <button
                            v-if="!editing.personal"
                            class="btn btn-warning btn-sm"
                            @click="editing.personal = true"
                          >
                            <i class="fa-solid fa-pen me-1"></i>
                            Editar
                          </button>
                          <button
                            v-if="editing.personal"
                            class="btn btn-info btn-sm"
                            @click="restoreBackup('personal')"
                          >
                            <i class="fa-solid fa-xmark me-1"></i>
                            Cancelar
                          </button>
                          <button
                            v-if="editing.personal"
                            class="btn btn-orange btn-sm"
                            :disabled="!is_personal_ready"
                            @click="updateEmployee('personal', employee)"
                          >
                            <i class="fa-solid fa-check me-1"></i>
                            Salvar
                          </button>
                        </div>
                      </h3>

                      <div class="hstack flex-wrap gap-3 text-dark">
                        <div
                          class="form-floating flex-fill"
                          style="width: 300px; min-width: 0;"
                        >
                          <input
                            v-model="employee.name"
                            type="text"
                            placeholder="Nome"
                            class="form-control bg-light-smooth"
                            :disabled="!editing.personal"
                          >
                          <label>
                            Nome
                          </label>
                        </div>

                        <div
                          class="form-floating flex-fill"
                          style="width: 300px; min-width: 0;"
                        >
                          <input
                            v-model="employee.surname"
                            type="text"
                            placeholder="Sobrenome"
                            class="form-control bg-light-smooth"
                            :disabled="!editing.personal"
                          >
                          <label>
                            Sobrenome
                          </label>
                        </div>

                        <div
                          class="input-group flex-fill"
                          style="width: 600px; min-width: 0;"
                        >
                          <span class="input-group-text bg-light-grey">
                            Nascimento
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
                              :disabled="!editing.personal"
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
                              :disabled="!editing.personal"
                            >
                            <label>Mês</label>
                          </div>

                          <div
                            class="form-floating flex-fill"
                            style="width: 300px; min-width: 0;"
                          >
                            <input
                              v-model="employee.birth_y"
                              type="number"
                              placeholder="00"
                              class="form-control bg-light-smooth"
                              :disabled="!editing.personal"
                            >
                            <label>Ano</label>
                          </div>
                        </div>

                        <div
                          class="form-floating flex-fill"
                          style="width: 300px; min-width: 0;"
                        >
                          <the-mask
                            v-model="employee.cpf"
                            :mask="['###.###.###-##']"
                            type="text"
                            placeholder="000.000.000-00"
                            class="form-control bg-light-smooth"
                            :disabled="!editing.personal"
                          />
                          <label>CPF</label>
                        </div>
                      </div>
                    </div>

                    <div class="bd-callout rounded vstack flex-fill gap-3" style="width: 600px; min-width: 0;">
                      <h3 class="hstack text-light fs-6">
                        Endereço
                        <div v-if="!editing.personal && !editing.job" class="ms-auto">
                          <button
                            v-if="!editing.address"
                            class="btn btn-warning btn-sm"
                            @click="editing.address = true"
                          >
                            <i class="fa-solid fa-pen me-1"></i>
                            Editar
                          </button>
                          <button
                            v-if="editing.address"
                            class="btn btn-info btn-sm"
                            @click="restoreBackup('address')"
                          >
                            <i class="fa-solid fa-xmark me-1"></i>
                            Cancelar
                          </button>
                          <button
                            v-if="editing.address"
                            class="btn btn-orange btn-sm"
                            :disabled="!is_address_ready"
                            @click="updateEmployee('address', address)"
                          >
                            <i class="fa-solid fa-check me-1"></i>
                            Salvar
                          </button>
                        </div>
                      </h3>

                      <div class="hstack flex-wrap gap-3 text-dark">
                        <div
                          class="form-floating flex-fill"
                          style="width: 600px; min-width: 0;"
                        >
                          <input
                            v-model="address.line"
                            type="text"
                            placeholder="Logradouro"
                            class="form-control bg-light-smooth"
                            :disabled="!editing.address"
                          >
                          <label>
                            Logradouro
                          </label>
                        </div>

                        <div
                          class="form-floating flex-fill"
                          style="width: 120px; min-width: 0;"
                        >
                          <input
                            v-model="address.number"
                            type="text"
                            placeholder="Número"
                            class="form-control bg-light-smooth"
                            :disabled="!editing.address"
                          >
                          <label>
                            Nº
                          </label>
                        </div>

                        <div
                          class="form-floating flex-fill"
                          style="width: 220px; min-width: 0;"
                        >
                          <input
                            v-model="address.number_info"
                            type="text"
                            placeholder="Complemento do número"
                            class="form-control bg-light-smooth"
                            :disabled="!editing.address"
                          >
                          <label>Compl. Nº</label>
                        </div>

                        <div
                          class="form-floating flex-fill"
                          style="width: 220px; min-width: 0;"
                        >
                          <input
                            v-model="address.district"
                            type="text"
                            placeholder="Cidade"
                            class="form-control bg-light-smooth"
                            :disabled="!editing.address"
                          >
                          <label>
                            Bairro
                          </label>
                        </div>

                        <div
                          class="form-floating flex-fill"
                          style="width: 220px; min-width: 0;"
                        >
                          <input
                            v-model="address.city"
                            type="text"
                            placeholder="Cidade"
                            class="form-control bg-light-smooth"
                            :disabled="!editing.address"
                          >
                          <label>
                            Cidade
                          </label>
                        </div>

                        <div
                          class="form-floating flex-fill"
                          style="width: 220px; min-width: 0;"
                        >
                          <select
                            v-model="address.uf"
                            type="text"
                            class="form-select bg-light-smooth"
                            :disabled="!editing.address"
                          >
                            <option
                              v-for="option in system_types.uf"
                              :key="option.value"
                              :value="option.value"
                            >{{option.label}}</option>
                          </select>
                          <label>UF</label>
                        </div>

                        <div
                          class="form-floating flex-fill"
                          style="width:220px; min-width: 0;"
                        >
                          <the-mask
                            v-model="address.cep"
                            :mask="['#####-###']"
                            type="text"
                            placeholder="CEP"
                            class="form-control bg-light-smooth"
                            :disabled="!editing.address"
                          />
                          <label>CEP</label>
                        </div>
                      </div>
                    </div>

                    <div class="bd-callout rounded vstack flex-fill gap-3" style="width: 600px; min-width: 0;">
                      <h3 class="hstack text-light fs-6">
                        Trabalho
                        <div v-if="!editing.address && !editing.personal" class="ms-auto">
                          <button
                            v-if="!editing.job"
                            class="btn btn-warning btn-sm"
                            @click="editing.job = true"
                          >
                            <i class="fa-solid fa-pen me-1"></i>
                            Editar
                          </button>
                          <button
                            v-if="editing.job"
                            class="btn btn-info btn-sm"
                            @click="restoreBackup('job')"
                          >
                            <i class="fa-solid fa-xmark me-1"></i>
                            Cancelar
                          </button>
                          <button
                            v-if="editing.job"
                            class="btn btn-orange btn-sm"
                            :disabled="!is_job_ready"
                            @click="updateEmployee('job', employee)"
                          >
                            <i class="fa-solid fa-check me-1"></i>
                            Salvar
                          </button>
                        </div>
                      </h3>

                      <div class="hstack flex-fill gap-3 text-dark">
                        <div
                          class="form-floating flex-fill"
                          style="width: 300px; min-width: 0;"
                        >
                          <select
                            v-model="employee.id_job_type"
                            type="number"
                            class="form-select bg-light-smooth"
                            :disabled="!editing.job"
                          >
                            <option
                              v-for="option in system_types.job"
                              :key="option.value"
                              :value="option.value"
                            >{{option.label}}</option>
                          </select>
                          <label>Cargo</label>
                        </div>

                        <div
                          class="form-floating flex-fill"
                          style="width: 300px; min-width: 0;"
                        >
                          <input
                            v-model="employee.job_depart"
                            type="text"
                            placeholder="Departamento"
                            class="form-control bg-light-smooth"
                            disabled
                          >
                          <label>Departamento</label>
                        </div>
                        <div
                          class="form-floating flex-fill"
                          style="width: 200px; min-width: 0;"
                        >
                          <money
                            v-model="employee.salary"
                            placeholder="Salário"
                            class="form-control bg-light-smooth"
                            disabled
                          />
                          <label>Salário</label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>


              <div class="tab-pane fade" id="nav-contacts">
                <div class="container-lg vstack flex-wrap gap-4 py-4">
                  <div class="hstack justify-content-start">
                    <button
                      data-bs-toggle="dropdown"
                      data-bs-auto-close="outside"
                      class="btn btn-warning btn-sm dropdown-toggle"
                    >
                      <i class="fa-solid fa-qrcode me-1"></i>
                      Ver QR Code
                    </button>
                    <ul
                      v-if="!editing.phones.editing && editing.phones.index == null"
                      class="dropdown-menu dropdown-menu-dark border border-light border-opacity-25 shadow-plus"
                    >
                      <li><span class="dropdown-item-text fw-normal">Escanear o QR Code abaixo para salvar o contato</span></li>
                      <li><hr class="dropdown-divider"></li>
                      <li class="hstack justify-content-center">
                        <qrcode-vue :value="vcard" :size="250" renderAs="svg" level="L"/>
                      </li>
                    </ul>
                  </div>

                  <h3 class="text-center text-light fs-3 m-0">Celulares</h3>

                  <div
                    v-if="phones.length"
                    class="vstack gap-3"
                  >
                    <div
                      v-for="(phone, index) in phones"
                      :key="phone.id_phone"
                      class="bd-callout rounded vstack flex-fill gap-3"
                    >
                      <h3 class="hstack justify-content-between text-light fs-6">
                        Celular {{index+1}}
                        <div class="ms-auto">
                          <button
                            :id="`deletePhone${index}`"
                            v-if="!editing.phones.editing && editing.phones.index == null"
                            data-bs-toggle="dropdown"
                            data-bs-auto-close="outside"
                            class="btn btn-danger btn-sm dropdown-toggle"
                          >
                            <i class="fa-solid fa-trash-can me-1"></i>
                            Excluir
                          </button>
                          <ul
                            v-if="!editing.phones.editing && editing.phones.index == null"
                            class="dropdown-menu dropdown-menu-dark border border-light border-opacity-25 shadow-plus"
                          >
                            <li><span class="dropdown-item-text text-warning fw-normal">O registro será excluído do sistema permanentemente.</span></li>
                            <li><span class="dropdown-item-text fw-normal">Deseja continuar?</span></li>
                            <li><hr class="dropdown-divider"></li>
                            <li class="hstack justify-content-end gap-2 px-3">
                              <button
                                class="btn btn-info btn-sm"
                                @click="closeDeletePhoneDropdown(index)"
                              >
                                <i class="fa-solid fa-xmark me-1"></i>
                                Não
                              </button>
                              <button
                                class="btn btn-orange btn-sm"
                                @click="closeDeletePhoneDropdown(index); deletePhone(index)"
                              >
                                <i class="fa-solid fa-check me-1"></i>
                                Sim
                              </button>
                            </li>
                          </ul>
                          <button
                            v-if="!editing.phones.editing && editing.phones.index == null"
                            class="btn btn-warning btn-sm"
                            @click="editing.phones.editing = true; editing.phones.index = index;"
                          >
                            <i class="fa-solid fa-pen me-1"></i>
                            Editar
                          </button>
                          <button
                            v-if="editing.phones.editing && editing.phones.index == index"
                            class="btn btn-info btn-sm"
                            @click="restoreBackup('phones')"
                          >
                            <i class="fa-solid fa-xmark me-1"></i>
                            Cancelar
                          </button>
                          <button
                            v-if="editing.phones.editing && editing.phones.index == index"
                            class="btn btn-orange btn-sm"
                            :disabled="!is_phones_ready"
                            @click="updatePhone(index)"
                          >
                            <i class="fa-solid fa-check me-1"></i>
                            Salvar
                          </button>
                        </div>
                      </h3>

                      <div class="text-dark hstack flex-wrap gap-3">
                        <div
                          class="input-group"
                          style="width: 400px; min-width: 0;"
                        >
                          <div
                            class="form-floating"
                          >
                            <the-mask
                              v-model="phone.phone"
                              :mask="['(##) ####-####', '(##) #####-####']"
                              type="text"
                              placeholder="Número"
                              class="form-control bg-light-smooth"
                              :disabled="!(editing.phones.editing && editing.phones.index == index)"
                            />
                            <label>Número</label>
                          </div>
                          <button
                            data-bs-toggle="dropdown"
                            data-bs-auto-close="outside"
                            class="btn btn-orange dropdown-toggle"
                          >
                            <i class="fa-brands fa-whatsapp"></i>
                          </button>
                          <div class="dropdown-menu dropdown-menu-dark border border-light border-opacity-25 shadow-plus">
                            <div class="vstack text-dark gap-2 px-3" style="width: 500px;">
                              <div class="form-floating">
                                <textarea
                                  v-model="whatsapp_inputs[index]"
                                  class="form-control"
                                  style="height: 125px;"
                                  name="body"
                                  placeholder="Mensagem"
                                  @input="whatsapp_texts[index]=encodeURIComponent(whatsapp_inputs[index])"
                                ></textarea>
                                <label>Texto da mensagem</label>
                              </div>
                            </div>
                            <hr class="dropdown-divider">
                            <div class="hstack justify-content-end gap-2 px-3">
                              <a
                                :href="`https://wa.me/%2B55${phone.phone}?text=${whatsapp_texts[index]}`"
                                class="btn btn-orange"
                                target="_blank"
                              >
                                <i class="fa-solid fa-up-right-from-square me-1"></i>
                                WhatsApp
                              </a>
                            </div>
                          </div>
                        </div>
                        <div
                          class="form-floating flex-fill"
                          style="width: 400px; min-width: 0;"
                        >
                          <input
                            v-model="phone.obs"
                            type="text"
                            placeholder="Observação"
                            class="form-control bg-light-smooth"
                            :disabled="!(editing.phones.editing && editing.phones.index == index)"
                          >
                          <label>Observação</label>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div
                    v-if="newPhone.length"
                    class="vstack gap-3"
                  >
                    <div
                      v-for="(phone, index) in newPhone"
                      :key="index"
                      class="bd-callout rounded vstack flex-fill gap-3"
                    >
                      <h3 class="hstack justify-content-between text-warning fs-6">
                        Novo Celular
                        <div class="ms-auto">
                          <button
                            class="btn btn-info btn-sm"
                            @click="removePhone(index)"
                          >
                            <i class="fa-solid fa-xmark me-1"></i>
                            Cancelar
                          </button>
                          <button
                            class="btn btn-orange btn-sm"
                            :disabled="!is_newPhone_ready"
                            @click="insertPhone"
                          >
                            <i class="fa-solid fa-check me-1"></i>
                            Salvar
                          </button>
                        </div>
                      </h3>

                      <div class="text-dark hstack flex-wrap gap-3">
                        <div
                          class="form-floating"
                          style="width: 400px; min-width: 0;"
                        >
                          <the-mask
                            v-model="phone.phone"
                            :mask="['(##) ####-####', '(##) #####-####']"
                            type="text"
                            placeholder="Número"
                            class="form-control bg-light-smooth"
                          />
                          <label>Número</label>
                        </div>
                        <div
                          class="form-floating flex-fill"
                          style="width: 400px; min-width: 0;"
                        >
                          <input
                            v-model="phone.obs"
                            type="text"
                            placeholder="Observação"
                            class="form-control bg-light-smooth"
                          >
                          <label>Observação</label>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="bd-callout vstack gap-3 rounded">
                    <h3 class="hstack text-warning fs-6 m-0">
                      <i class="fa-solid fa-circle-info me-1"></i>
                      Adicione um celular
                      <div class="ms-auto">
                        <button
                          class="btn btn-warning btn-sm"
                          @click="addPhone"
                        >
                          <i class="fa-solid fa-mobile-screen me-1"></i>
                          Adicionar
                        </button>
                      </div>
                    </h3>
                  </div>


                  <h3 class="text-center text-light fs-3 m-0">E-mails</h3>

                  <div
                    v-if="emails.length"
                    class="vstack gap-3"
                  >
                    <div
                      v-for="(email, index) in emails"
                      :key="email.id_email"
                      class="bd-callout rounded vstack flex-fill gap-3"
                    >
                      <h3 class="hstack justify-content-between text-light fs-6">
                        E-mail {{index+1}}
                        <div class="ms-auto">
                          <button
                            :id="`deleteEmail${index}`"
                            v-if="!editing.emails.editing && editing.emails.index == null"
                            data-bs-toggle="dropdown"
                            data-bs-auto-close="outside"
                            class="btn btn-danger btn-sm dropdown-toggle"
                          >
                            <i class="fa-solid fa-trash-can me-1"></i>
                            Excluir
                          </button>
                          <ul
                            v-if="!editing.emails.editing && editing.emails.index == null"
                            class="dropdown-menu dropdown-menu-dark border border-light border-opacity-25 shadow-plus"
                          >
                            <li><span class="dropdown-item-text text-warning fw-normal">O registro será excluído do sistema permanentemente.</span></li>
                            <li><span class="dropdown-item-text fw-normal">Deseja continuar?</span></li>
                            <li><hr class="dropdown-divider"></li>
                            <li class="hstack justify-content-end gap-2 px-3">
                              <button
                                class="btn btn-info btn-sm"
                                @click="closeDeleteEmailDropdown(index)"
                              >
                                <i class="fa-solid fa-xmark me-1"></i>
                                Não
                              </button>
                              <button
                                class="btn btn-orange btn-sm"
                                @click="closeDeleteEmailDropdown(index); deleteEmail(index)"
                              >
                                <i class="fa-solid fa-check me-1"></i>
                                Sim
                              </button>
                            </li>
                          </ul>
                          <button
                            v-if="!editing.emails.editing && editing.emails.index == null"
                            class="btn btn-warning btn-sm"
                            @click="editing.emails.editing = true; editing.emails.index = index;"
                          >
                            <i class="fa-solid fa-pen me-1"></i>
                            Editar
                          </button>
                          <button
                            v-if="editing.emails.editing && editing.emails.index == index"
                            class="btn btn-info btn-sm"
                            @click="restoreBackup('emails')"
                          >
                            <i class="fa-solid fa-xmark me-1"></i>
                            Cancelar
                          </button>
                          <button
                            v-if="editing.emails.editing && editing.emails.index == index"
                            class="btn btn-orange btn-sm"
                            :disabled="!is_emails_ready"
                            @click="updateEmail(index)"
                          >
                            <i class="fa-solid fa-check me-1"></i>
                            Salvar
                          </button>
                        </div>
                      </h3>

                      <div class="text-dark hstack gap-3">
                        <div
                          class="input-group"
                          style="width: 400px; min-width: 0;"
                        >
                          <div class="form-floating">
                            <input
                              v-model="email.email"
                              type="email"
                              placeholder="Número"
                              class="form-control bg-light-smooth"
                              :disabled="!(editing.emails.editing && editing.emails.index == index)"
                            >
                            <label>Endereço</label>
                          </div>
                          <button
                            data-bs-toggle="dropdown"
                            data-bs-auto-close="outside"
                            class="btn btn-orange dropdown-toggle"
                          >
                            <i class="fa-regular fa-envelope"></i>
                          </button>
                          <div class="dropdown d-inline">
                            <form
                              class="dropdown-menu dropdown-menu-dark border border-light border-opacity-25 shadow-plus"
                              :action="`mailto:${email.email}`"
                              method="GET"
                              enctype="text/plain"
                              target="_blank"
                            >
                              <div class="vstack text-dark gap-2 px-3" style="width: 500px;">
                                <div class="input-group">
                                  <span class="input-group-text">Assunto</span>
                                  <div class="form-floating">
                                    <input class="form-control" name="subject" placeholder="Assunto" type="text" />
                                    <label>Assunto do E-mail</label>
                                  </div>
                                </div>
                                <div class="input-group">
                                  <span class="input-group-text">cc</span>
                                  <div class="form-floating">
                                    <input class="form-control" name="cc" placeholder="Cópia" type="email" />
                                    <label>Enviar cópia para</label>
                                  </div>
                                </div>
                                <div class="input-group">
                                  <span class="input-group-text">bcc</span>
                                  <div class="form-floating">
                                    <input class="form-control" name="bcc" placeholder="Cópia oculta" type="email" />
                                    <label>Enviar cópia oculta para</label>
                                  </div>
                                </div>
                                <div class="form-floating">
                                  <textarea
                                    class="form-control"
                                    style="height: 125px;"
                                    name="body"
                                    placeholder="Mensagem"
                                  ></textarea>
                                  <label>Texto da mensagem</label>
                                </div>
                              </div>
                              <hr class="dropdown-divider">
                              <div class="hstack justify-content-end px-3">
                                <button class="btn btn-orange" type="submit" value="Preparar E-mail">
                                  <i class="fa-solid fa-up-right-from-square me-1"></i>
                                  E-mail
                                </button>
                              </div>
                            </form>
                          </div>
                        </div>
                        <div
                          class="form-floating flex-fill"
                          style="width: 400px; min-width: 0;"
                        >
                          <input
                            v-model="email.obs"
                            type="text"
                            placeholder="obs"
                            class="form-control bg-light-smooth"
                            :disabled="!(editing.emails.editing && editing.emails.index == index)"
                          >
                          <label>Observação</label>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div
                    v-if="newEmail.length"
                    class="vstack gap-3"
                  >
                    <div
                      v-for="(email, index) in newEmail"
                      :key="index"
                      class="bd-callout rounded vstack flex-fill gap-3"
                    >
                      <h3 class="hstack justify-content-between text-warning fs-6">
                        Novo E-mail
                        <div class="ms-auto">
                          <button
                            class="btn btn-info btn-sm"
                            @click="removeEmail(index)"
                          >
                            <i class="fa-solid fa-xmark me-1"></i>
                            Cancelar
                          </button>
                          <button
                            class="btn btn-orange btn-sm"
                            :disabled="!is_newEmail_ready"
                            @click="insertEmail"
                          >
                            <i class="fa-solid fa-check me-1"></i>
                            Salvar
                          </button>
                        </div>
                      </h3>

                      <div class="text-dark hstack gap-3">
                        <div
                          class="form-floating"
                          style="width: 400px; min-width: 0;"
                        >
                          <input
                            v-model="email.email"
                            type="email"
                            placeholder="Endereço"
                            class="form-control bg-light-smooth"
                          >
                          <label>Endereço</label>
                        </div>
                        <div
                          class="form-floating flex-fill"
                          style="width: 400px; min-width: 0;"
                        >
                          <input
                            v-model="email.obs"
                            type="text"
                            placeholder="Observação"
                            class="form-control bg-light-smooth"
                          >
                          <label>Observação</label>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="bd-callout vstack gap-3 rounded">
                    <h3 class="hstack text-warning fs-6 m-0">
                      <i class="fa-solid fa-circle-info me-1"></i>
                      Adicione um e-mail
                      <div class="ms-auto">
                        <button
                          class="btn btn-warning btn-sm"
                          @click="addEmail"
                        >
                          <i class="fa-solid fa-at me-1"></i>
                          Adicionar
                        </button>
                      </div>
                    </h3>
                  </div>
                </div>
              </div>


              <div class="tab-pane fade" id="nav-family">
                <div class="container-lg vstack flex-wrap gap-4 py-4">
                  <div
                    v-if="family.length"
                    class="vstack gap-3"
                  >
                    <div
                      v-for="(person, index) in family"
                      :key="index"
                      class="bd-callout vstack gap-3 rounded"
                    >
                      <h3 class="hstack justify-content-between text-light fs-6">
                        Dependente {{index+1}}
                        <div class="ms-auto">
                          <button
                            :id="`deleteFamily${index}`"
                            v-if="!editing.family.editing && editing.family.index == null"
                            data-bs-toggle="dropdown"
                            data-bs-auto-close="outside"
                            class="btn btn-danger btn-sm dropdown-toggle"
                          >
                            <i class="fa-solid fa-trash-can me-1"></i>
                            Excluir
                          </button>
                          <ul
                            v-if="!editing.family.editing && editing.family.index == null"
                            class="dropdown-menu dropdown-menu-dark border border-light border-opacity-25 shadow-plus"
                          >
                            <li><span class="dropdown-item-text text-warning fw-normal">O registro será excluído do sistema permanentemente.</span></li>
                            <li><span class="dropdown-item-text fw-normal">Deseja continuar?</span></li>
                            <li><hr class="dropdown-divider"></li>
                            <li class="hstack justify-content-end gap-2 px-3">
                              <button
                                class="btn btn-info btn-sm"
                                @click="closeDeleteFamilyDropdown(index)"
                              >
                                <i class="fa-solid fa-xmark me-1"></i>
                                Não
                              </button>
                              <button
                                class="btn btn-orange btn-sm"
                                @click="closeDeleteFamilyDropdown(index); deleteFamily(index)"
                              >
                                <i class="fa-solid fa-check me-1"></i>
                                Sim
                              </button>
                            </li>
                          </ul>
                          <button
                            v-if="!editing.family.editing && editing.family.index == null"
                            class="btn btn-warning btn-sm"
                            @click="editing.family.editing = true; editing.family.index = index;"
                          >
                            <i class="fa-solid fa-pen me-1"></i>
                            Editar
                          </button>
                          <button
                            v-if="editing.family.editing && editing.family.index == index"
                            class="btn btn-info btn-sm"
                            @click="restoreBackup('family')"
                          >
                            <i class="fa-solid fa-xmark me-1"></i>
                            Cancelar
                          </button>
                          <button
                            v-if="editing.family.editing && editing.family.index == index"
                            class="btn btn-orange btn-sm"
                            :disabled="!is_family_ready"
                            @click="updateFamily(index)"
                          >
                            <i class="fa-solid fa-check me-1"></i>
                            Salvar
                          </button>
                        </div>
                      </h3>

                      <div class="hstack flex-wrap gap-3 text-dark">
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
                              :disabled="!(editing.family.editing && editing.family.index == index)"
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
                              placeholder="Sobrenome"
                              class="form-control bg-light-smooth"
                              :disabled="!(editing.family.editing && editing.family.index == index)"
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
                                :disabled="!(editing.family.editing && editing.family.index == index)"
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
                                :disabled="!(editing.family.editing && editing.family.index == index)"
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
                                :disabled="!(editing.family.editing && editing.family.index == index)"
                              >
                              <label>Ano</label>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="hstack flex-wrap gap-3 text-dark">
                        <div
                          class="form-floating flex-fill"
                          style="width: 300px; min-width: 0;"
                        >
                          <the-mask
                            v-model="person.cpf"
                            :mask="['###.###.###-##']"
                            type="text"
                            placeholder="000.000.000-00"
                            class="form-control bg-light-smooth"
                            :disabled="!(editing.family.editing && editing.family.index == index)"
                          />
                          <label>CPF</label>
                        </div>

                        <div
                          class="form-floating flex-fill"
                          style="width: 300px; min-width: 0;"
                        >
                          <select
                            v-model="person.id_family_type"
                            class="form-select"
                            :disabled="!(editing.family.editing && editing.family.index == index)"
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
                    v-if="newFamily.length"
                    class="vstack gap-3"
                  >
                    <div
                      v-for="(person, index) in newFamily"
                      :key="index"
                      class="bd-callout vstack gap-3 rounded"
                    >
                      <h3 class="hstack justify-content-between text-warning fs-6">
                        Novo Dependente
                        <div class="ms-auto">
                          <button
                            class="btn btn-info btn-sm"
                            @click="removeFamily(index)"
                          >
                            <i class="fa-solid fa-xmark me-1"></i>
                            Cancelar
                          </button>
                          <button
                            class="btn btn-orange btn-sm"
                            :disabled="!is_newFamily_ready"
                            @click="insertFamily"
                          >
                            <i class="fa-solid fa-check me-1"></i>
                            Salvar
                          </button>
                        </div>
                      </h3>

                      <div class="hstack flex-wrap gap-3 text-dark">
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

                      <div class="hstack flex-wrap gap-3 text-dark">
                        <div
                          class="form-floating flex-fill"
                          style="width: 300px; min-width: 0;"
                        >
                          <the-mask
                            v-model="person.cpf"
                            :mask="['###.###.###-##']"
                            type="text"
                            placeholder="000.000.000-00"
                            class="form-control bg-light-smooth"
                          />
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
                          :disabled="newFamily.length > 0"
                        >
                          <i class="fa-solid fa-user-plus me-1"></i>
                          Adicionar
                        </button>
                      </div>
                    </h3>
                  </div>
                </div>
              </div>


              <div class="tab-pane fade" id="nav-user">
                <div class="container-lg vstack flex-wrap gap-4 py-4">
                  <div
                    v-if="user.id_user"
                    class="bd-callout vstack gap-3 rounded"
                  >
                    <h3 class="hstack text-light fs-6 m-0">
                      Usuário
                      <div class="hstack gap-1 ms-auto">
                        <div>
                          <button
                            v-if="session_user.id_user == user.id_user"
                            ref="alterPassButton"
                            data-bs-toggle="dropdown"
                            data-bs-auto-close="outside"
                            class="btn btn-info btn-sm dropdown-toggle"
                          >
                            <i class="fa-solid fa-key me-1"></i>
                            Alterar Senha
                          </button>
                          <div
                            v-if="session_user.id_user == user.id_user"
                            class="dropdown-menu dropdown-menu-dark border border-light border-opacity-25 shadow-plus py-2"
                          >
                            <div class="hstack gap-2 py-2 px-3">
                              <input
                                class="form-control"
                                type="password"
                                placeholder="senha atual"
                                v-model="user_pass.old_pass"
                                @keyup.enter="alterPass"
                              >
                              <input
                                class="form-control"
                                type="password"
                                placeholder="senha nova"
                                v-model="user_pass.new_pass"
                                @keyup.enter="alterPass"
                              >
                            </div>
                            <hr class="dropdown-divider">
                            <div class="hstack justify-content-end gap-2 px-3">
                              <button
                                class="btn btn-info btn-sm"
                                @click="$refs.alterPassButton.click()"
                              >
                                <i class="fa-solid fa-xmark me-1"></i>
                                Cancelar
                              </button>
                              <button
                                class="btn btn-orange btn-sm"
                                :disabled="
                                  (!user_pass.old_pass || !user_pass.new_pass) ||
                                  (user_pass.old_pass == user_pass.new_pass)
                                "
                                @click="alterPass"
                              >
                                <i class="fa-solid fa-check me-1"></i>
                                Alterar
                              </button>
                            </div>
                          </div>
                        </div>

                        <div>
                          <button
                            v-if="session_user.id_group <= 4"
                            ref="resetPassButton"
                            data-bs-toggle="dropdown"
                            data-bs-auto-close="outside"
                            class="btn btn-orange btn-sm dropdown-toggle"
                          >
                            <i class="fa-solid fa-key me-1"></i>
                            Resetar Senha
                          </button>
                          <ul
                            v-if="session_user.id_group <= 4"
                            class="dropdown-menu dropdown-menu-dark border border-light border-opacity-25 shadow-plus"
                          >
                            <li><span class="dropdown-item-text text-warning fw-normal">A senha será resetada para o padrão: mestre123</span></li>
                            <li><span class="dropdown-item-text fw-normal">Deseja continuar?</span></li>
                            <li><hr class="dropdown-divider"></li>
                            <li class="hstack justify-content-end gap-2 px-3">
                              <button
                                class="btn btn-info btn-sm"
                                @click="$refs.resetPassButton.click()"
                              >
                                <i class="fa-solid fa-xmark me-1"></i>
                                Não
                              </button>
                              <button
                                class="btn btn-orange btn-sm"
                                @click="resetPass"
                              >
                                <i class="fa-solid fa-check me-1"></i>
                                Sim
                              </button>
                            </li>
                          </ul>
                        </div>
                      </div>
                    </h3>

                    <div class="hstack flex-wrap gap-3 align-items-stretch text-dark">
                      <div class="input-group flex-fill" style="width: 220px; min-width: 0;">
                        <div class="form-floating">
                          <input
                            :value="user.user"
                            type="text"
                            placeholder="Telefone"
                            class="form-control bg-light-smooth"
                            disabled
                          >
                          <label>
                            Usuário
                          </label>
                        </div>
                        <span class="input-group-text bg-light-grey">{{user.postfix}}</span>
                      </div>

                      <div
                        class="form-floating flex-fill"
                        style="width: 220px; min-width: 0;"
                      >
                        <input
                          :value="user.user_group"
                          type="text"
                          placeholder="Grupo"
                          class="form-control bg-light-smooth"
                          disabled
                        >
                        <label>
                          Grupo
                        </label>
                      </div>
                    </div>
                  </div>

                  <div
                    v-if="!user.id_user"
                    class="bd-callout vstack gap-3 rounded"
                  >
                    <h3 class="hstack text-light fs-6 m-0">
                      Usuário
                      <div class="ms-auto">
                        <button
                          v-if="!user.create"
                          class="btn btn-warning btn-sm"
                          @click="user.create = true"
                        >
                          <i class="fa-solid fa-user-plus me-1"></i>
                          Criar
                        </button>
                        <button
                          v-if="user.create"
                          class="btn btn-info btn-sm"
                          @click="user.create = false"
                        >
                          <i class="fa-solid fa-user-xmark me-1"></i>
                          Cancelar
                        </button>
                        <button
                          v-if="user.create"
                          class="btn btn-orange btn-sm"
                          :disabled="!is_user_ready"
                          @click="insertUser"
                        >
                          <i class="fa-solid fa-user-check me-1"></i>
                          Salvar
                        </button>
                      </div>
                    </h3>

                    <div v-if="user.create" class="hstack flex-wrap gap-3 text-dark">
                      <div class="input-group flex-fill" style="width: 220px; min-width: 0;">
                        <div class="form-floating">
                          <input
                            v-model="user.user"
                            type="text"
                            placeholder="Telefone"
                            class="form-control bg-light-smooth"
                            :class="{
                              'is-invalid': (
                                !user_availability.available &&
                                user_availability.available != undefined
                              )
                            }"
                            :disabled="!user.create"
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
                          v-model="user.pass"
                          type="password"
                          placeholder="E-mail"
                          class="form-control bg-light-smooth"
                          :disabled="!user.create"
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
                          v-model="user.id_user_group"
                          class="form-select"
                          :disabled="!user.create"
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
        backup: {},
        editing: {
          image:    false,
          personal: false,
          address:  false,
          job:      false,
          user:     false,
          phones: {
            editing: false,
            index:   null,
          },
          emails: {
            editing: false,
            index:   null,
          },
          family: {
            editing: false,
            index:   null,
          },
        },
        imageSrc: '/img/user.png',
        employee: {
          id_entity:     null,
          id_person:     null,
          id_employee:   null,
          id_job_type:   null,
          job_type:      null,
          id_job_depart: null,
          job_depart:    null,
          salary:        0,
          name:          null,
          surname:       null,
          birth_d:       null,
          birth_m:       null,
          birth_y:       null,
          cpf:           null,
          active:        null,
        },
        address:         {},
        phones:          [],
        newPhone:        [],
        whatsapp_inputs: [],
        whatsapp_texts:  [],
        emails:          [],
        newEmail:        [],
        family:          [],
        newFamily:       [],
        user: {
          create:        false,
          id_user_group: null,
          user_group:    null,
          id_user:       null,
          user:          null,
          postfix:       null,
          pass:          null,
        },
        user_availability: {
          available: undefined,
          timer:     undefined,
        },
        user_pass: {
          old_pass: null,
          new_pass: null,
        },
      }
    },

    computed: {
      vcard(){
        var vcard_string = `BEGIN:VCARD\nVERSION:4.0\nN:${this.employee.surname};${this.employee.name};;;\nFN:${this.employee.name} ${this.employee.surname}\n`;
        this.phones.forEach(e => { vcard_string = vcard_string+`TEL;TYPE=cell:${e.phone}\n`; });
        this.emails.forEach(e => { vcard_string = vcard_string+`EMAIL:${e.email}\n`; });
        vcard_string = vcard_string+`END:VCARD`;
        console.log(vcard_string);
        return vcard_string;
      },
      is_personal_ready(){
        if(!this.editing.personal){ return undefined; }
        if(
          (this.employee.name    != this.backup.employee.name    && this.employee.name   ) ||
          (this.employee.surname != this.backup.employee.surname && this.employee.surname) ||
          (this.employee.birth_d != this.backup.employee.birth_d && this.employee.birth_d) ||
          (this.employee.birth_m != this.backup.employee.birth_m && this.employee.birth_m) ||
          (this.employee.birth_y != this.backup.employee.birth_y && this.employee.birth_y) ||
          (this.employee.cpf     != this.backup.employee.cpf)
        ){ return true; } else { return false; }
      },

      is_address_ready(){
        if(!this.editing.address){ return undefined; }
        if(
          (this.address.line        != this.backup.address.line     && this.address.line    ) ||
          (this.address.number      != this.backup.address.number   && this.address.number  ) ||
          (this.address.district    != this.backup.address.district && this.address.district) ||
          (this.address.city        != this.backup.address.city     && this.address.city    ) ||
          (this.address.uf          != this.backup.address.uf       && this.address.uf      ) ||
          (this.address.number_info != this.backup.address.number_info)                       ||
          (this.address.cep         != this.backup.address.cep)
        ){ return true; } else { return false; }
      },

      is_job_ready(){
        if(!this.editing.job){ return undefined; }
        if(this.employee.id_job_type != this.backup.employee.id_job_type && this.employee.id_job_type)
        { return true; } else { return false; }
      },

      is_user_ready(){
        if(!this.user.create){ return undefined; }
        if(
          this.user.create                 &&
          this.user_availability.available &&
          this.user.pass                   &&
          this.user.id_user_group
        ){ return true; } else { return false; }
      },

      is_phones_ready(){
        if(!this.editing.phones.editing){ return undefined; }
        if(
          this.editing.phones.editing && (
            (this.phones[this.editing.phones.index].phone != this.backup.phones[this.editing.phones.index].phone && this.phones[this.editing.phones.index].phone) ||
            (this.phones[this.editing.phones.index].obs  != this.backup.phones[this.editing.phones.index].obs)
          )
        ){ return true; } else { return false; }
      },

      is_newPhone_ready(){
        if(!this.newPhone.length){ return undefined; }
        if(this.newPhone.length){
          var ready;
          for( let i = 0; i < this.newPhone.length; i++ ){
            if(!this.newPhone[i].phone){
              ready = false;
              break;
            }
          }
          if(ready === false){ return false; } else { return true; }
        }
      },

      is_emails_ready(){
        if(!this.editing.emails.editing){ return undefined; }
        if(
          this.editing.emails.editing && (
            (this.emails[this.editing.emails.index].email != this.backup.emails[this.editing.emails.index].email && this.emails[this.editing.emails.index].email) ||
            (this.emails[this.editing.emails.index].obs   != this.backup.emails[this.editing.emails.index].obs)
          )
        ){ return true; } else { return false; }
      },

      is_newEmail_ready(){
        if(!this.newEmail.length){ return undefined; }
        if(this.newEmail.length){
          var ready;
          for( let i = 0; i < this.newEmail.length; i++ ){
            if(!this.newEmail[i].email){
              ready = false;
              break;
            }
          }
          if(ready === false){ return false; } else { return true; }
        }
      },

      is_family_ready(){
        if(!this.editing.family.editing){ return undefined; }
        if(
          this.editing.family.editing && (
            (this.family[this.editing.family.index].name           != this.backup.family[this.editing.family.index].name           && this.family[this.editing.family.index].name          ) ||
            (this.family[this.editing.family.index].surname        != this.backup.family[this.editing.family.index].surname        && this.family[this.editing.family.index].surname       ) ||
            (this.family[this.editing.family.index].birth_d        != this.backup.family[this.editing.family.index].birth_d        && this.family[this.editing.family.index].birth_d       ) ||
            (this.family[this.editing.family.index].birth_m        != this.backup.family[this.editing.family.index].birth_m        && this.family[this.editing.family.index].birth_m       ) ||
            (this.family[this.editing.family.index].birth_y        != this.backup.family[this.editing.family.index].birth_y        && this.family[this.editing.family.index].birth_y       ) ||
            (this.family[this.editing.family.index].id_family_type != this.backup.family[this.editing.family.index].id_family_type && this.family[this.editing.family.index].id_family_type) ||
            (this.family[this.editing.family.index].cpf            != this.backup.family[this.editing.family.index].cpf)
          )
        ){ return true; } else { return false; }
      },

      is_newFamily_ready(){
        if(!this.newFamily.length){ return undefined; }
        if(this.newFamily.length){
          var ready;
          for( let i = 0; i < this.newFamily.length; i++ ){
            if(
              !this.newFamily[i].id_family_type ||
              !this.newFamily[i].name           ||
              !this.newFamily[i].surname        ||
              !this.newFamily[i].birth_d        ||
              !this.newFamily[i].birth_m        ||
              !this.newFamily[i].birth_y
            ){
              ready = false;
              break;
            }
          }
          if(ready === false){ return false; } else { return true; }
        }
      },

      user_create(){ return this.user.create; },
    },

    watch: {
      user_create(){
        if (!this.user.create){
          this.user_availability.available = undefined;
          this.user.id_user_group          = null;
          this.user.user                   = null;
          this.user.pass                   = null;
        }
      },
      phones(){
        var inputs = [];
        var texts  = [];
        for(let i = 0; i < this.phones.length; i++){
          inputs[i] = '';
          texts[i]  = '';
        }
        this.whatsapp_inputs = JSON.parse(JSON.stringify(inputs));
        this.whatsapp_texts  = JSON.parse(JSON.stringify(texts));
      },
    },

    created(){},

    mounted(){},

    updated(){},

    activated(){},

    deactivated(){},

    methods: {
      openProfileModal(args){
        this.$emit('isLoading', true);
        this.getEmployee(args.id_employee)
          .then(()=>{
            switch(args.tab){
              case 'info':
                this.$refs.infoTab.click();
                break;
              case 'contacts':
                this.$refs.contactsTab.click();
                break;
              case 'family':
                this.$refs.familyTab.click();
                break;
              case 'user':
                this.$refs.userTab.click();
                break;
            }
            bootstrap.Modal.getOrCreateInstance(this.$refs.profileModal).show();
          })
          .catch((error)=>{
            console.log(error);
            this.$emit('showAlert', 'error', `Ocorreu um erro no processo: ${error}`);
          })
          .finally(()=>{
            this.$emit('isLoading', false);
          });
      },

      openCroppImageModal(){
        this.editing.image = true;
        bootstrap.Modal.getOrCreateInstance(this.$refs.croppImageModal).show();
      },

      closeCroppImageModal(){
        bootstrap.Modal.getOrCreateInstance(this.$refs.croppImageModal).hide();
        this.editing.image = false;
      },

      async getEmployee(id_employee){
        api.getEmployee(id_employee)
          .then((response)=>{
            if(response.data.success){
              this.backup   = JSON.parse(JSON.stringify(response.data));
              this.employee = response.data.employee;
              this.address  = response.data.address;
              this.family   = response.data.family;
              this.phones   = response.data.phones;
              this.emails   = response.data.emails;
              response.data.user?
                this.user = {
                  create:        response.data.user.create == null? false: true,
                  id_user_group: response.data.user.id_user_group,
                  user_group:    response.data.user.user_group,
                  id_user:       response.data.user.id_user,
                  user:          response.data.user.user.split('@')[0],
                  postfix:       this.session_user.postfix,
                  pass:          null
                }:
                this.user = {
                  create:        false,
                  id_user_group: null,
                  user_group:    null,
                  id_user:       null,
                  user:          null,
                  postfix:       this.session_user.postfix,
                  pass:          null
                };
              this.getImage();
            }
          })
          .catch((error)=>{
            console.log(error);
            this.$emit('showAlert', 'error', `Ocorreu um erro no processo: ${error}`);
          });
      },

      insertImage(blob){
        this.$emit('isLoading', true);
        const formData = new FormData();
        formData.append('image', blob, 'image.png');
        formData.append('id_person', this.employee.id_person);
        api.insertImage(formData)
          .then((response)=>{
            if(response.data.success){
              this.closeCroppImageModal();
              this.$emit('showAlert', 'success', 'Imagem cadastrada com sucesso.');
              this.getImage();
            }
          })
          .catch((error)=>{
            console.log(error);
            this.$emit('showAlert', 'error', `Ocorreu um erro no processo: ${error}`);
          })
          .finally(()=>{
            this.$emit('isLoading', false);
          });
      },

      getImage(){
        api.getImage(this.employee.id_person)
          .then((response)=>{
            if(response.headers.success == 'true'){
              const blob = new Blob([response.data], { type: 'image/png' });
              this.imageSrc = URL.createObjectURL(blob);
            }
          })
          .catch((error)=>{
            console.log(error);
            this.$emit('showAlert', 'error', `Ocorreu um erro no processo: ${error}`);
          })
          .finally(()=>{
            this.$emit('isLoading', false);
          });
      },

      checkUserAvailability(){
        clearTimeout(this.user_availability.timer);
        this.user_availability.timer = setTimeout(() => {
          api.isUserAvailable(`${this.user.user}${this.user.postfix}`)
            .then((response)=>{
              this.user_availability.available = response.data.available;
            })
            .catch((error)=>{
              console.log(error);
              this.$emit('showAlert', 'error', `Ocorreu um erro no processo: ${error}`);
            })
        }, 600);
      },

      insertUser(){
        this.$emit('isLoading', true);
        api.insertUser(this.employee.id_employee, this.user)
          .then((response)=>{
            if(response.data.success){
              this.getEmployee(this.employee.id_employee)
                .then(()=>{
                  this.$emit('showAlert', 'success', 'Usuário cadastrado com sucesso.');
                })
            }
          })
          .catch((error)=>{
            console.log(error);
            this.$emit('showAlert', 'error', `Ocorreu um erro no processo: ${error}`);
          })
          .finally(()=>{
            this.$emit('isLoading', false);
          });
      },

      resetPass(){
        this.$emit('isLoading', true);
        this.$refs.resetPassButton.click();
        api.resetPass(this.user.id_user)
          .then((response)=>{
            if(response.data.success){
              this.getEmployee(this.employee.id_employee)
                .then(()=>{
                  this.$emit('showAlert', 'success', 'Senha resetada com sucesso.');
                })
            }
          })
          .catch((error)=>{
            console.log(error);
            this.$emit('showAlert', 'error', `Ocorreu um erro no processo: ${error}`);
          })
          .finally(()=>{
            this.$emit('isLoading', false);
          });
      },

      alterPass(){
        if(!this.user_pass.old_pass || !this.user_pass.new_pass){
          return this.$emit('showAlert', 'warning', 'Senha atual e senha nova devem ser preenchidas.');
        }
        if(this.user_pass.old_pass == this.user_pass.new_pass){
          return this.$emit('showAlert', 'warning', 'Senha atual e senha nova não podem ser iguais.');
        }
        this.$emit('isLoading', true);
        this.$refs.alterPassButton.click();
        api.alterPass(this.user.id_user, this.user_pass.old_pass, this.user_pass.new_pass)
          .then((response)=>{
            if(response.data.success){
              this.$emit('showAlert', 'success', 'Senha alterada com sucesso.');
            } else {
              switch(response.data.error){
                case 'old_pass':
                  this.$emit('showAlert', 'error', 'Senha atual inválida.');
                  break;
                case 'new_pass':
                  this.$emit('showAlert', 'error', 'Senha nova é igual a senha atual.');
                  break;
                case 'sql':
                  this.$emit('showAlert', 'error', 'SQL inválido, informe o suporte.');
                  break;
              };
            }
          })
          .catch((error)=>{
            console.log(error);
            this.$emit('showAlert', 'error', `Ocorreu um erro no processo: ${error}`);
          })
          .finally(()=>{
            this.user_pass.old_pass = null;
            this.user_pass.new_pass = null;
            this.$emit('isLoading', false);
          });
      },

      insertFamily(){
        this.$emit('isLoading', true);
        api.insertFamily(
          this.employee.id_entity,
          this.employee.id_employee,
          this.newFamily
        )
          .then((response)=>{
            if(response.data.success){
              this.newFamily = [];
              this.getEmployee(this.employee.id_employee)
                .then(()=>{
                  this.$emit('showAlert', 'success', 'Dependente cadastrado com sucesso.');
                });
            }
          })
          .catch((error)=>{
            console.log(error);
          })
          .finally(()=>{
            this.$emit('isLoading', false);
          });
      },

      updateFamily(index){
        this.$emit('isLoading', true);
        api.updateFamily(this.family[index])
          .then((response)=>{
            if(response.data.success){
              this.editing.family.editing = false;
              this.editing.family.index   = null;
              this.getEmployee(this.employee.id_employee)
                .then(()=>{
                  this.$emit('showAlert', 'success', 'Dependente atualizado com sucesso.');
                });
            }
          })
          .catch((error)=>{
            console.log(error);
            this.$emit('showAlert', 'error', `Ocorreu um erro no processo: ${error}`);
          })
          .finally(()=>{
            this.$emit('isLoading', false);
          });
      },

      deleteFamily(index){
        this.$emit('isLoading', true);
        api.deleteFamily(this.family[index].id_family, this.family[index].id_person)
          .then((response)=>{
            if(response.data.success){
              this.getEmployee(this.employee.id_employee)
                .then(()=>{
                  this.$emit('showAlert', 'success', 'Dependente excluído com sucesso.');
                })
            }
          })
          .catch((error)=>{
            console.log(error);
            this.$emit('showAlert', 'error', `Ocorreu um erro no processo: ${error}`);
          })
          .finally(()=>{
            this.$emit('isLoading', false);
          });
      },

      insertEmail(){
        this.$emit('isLoading', true);
        api.insertEmail(
          this.employee.id_person,
          this.newEmail
        )
          .then((response)=>{
            if(response.data.success){
              this.newEmail = [];
              this.getEmployee(this.employee.id_employee)
                .then(()=>{
                  this.$emit('showAlert', 'success', 'E-mail cadastrado com sucesso.');
                });
            }
          })
          .catch((error)=>{
            console.log(error);
          })
          .finally(()=>{
            this.$emit('isLoading', false);
          });
      },

      updateEmail(index){
        this.$emit('isLoading', true);
        api.updateEmail(this.emails[index])
          .then((response)=>{
            if(response.data.success){
              this.editing.emails.editing = false;
              this.editing.emails.index   = null;
              this.getEmployee(this.employee.id_employee)
                .then(()=>{
                  this.$emit('showAlert', 'success', 'E-mail atualizado com sucesso.');
                });
            }
          })
          .catch((error)=>{
            console.log(error);
            this.$emit('showAlert', 'error', `Ocorreu um erro no processo: ${error}`);
          })
          .finally(()=>{
            this.$emit('isLoading', false);
          });
      },

      deleteEmail(index){
        this.$emit('isLoading', true);
        api.deleteEmail(this.emails[index].id_email)
          .then((response)=>{
            if(response.data.success){
              this.getEmployee(this.employee.id_employee)
                .then(()=>{
                  this.$emit('showAlert', 'success', 'E-mail excluído com sucesso.');
                })
            }
          })
          .catch((error)=>{
            console.log(error);
            this.$emit('showAlert', 'error', `Ocorreu um erro no processo: ${error}`);
          })
          .finally(()=>{
            this.$emit('isLoading', false);
          });
      },

      insertPhone(){
        this.$emit('isLoading', true);
        api.insertPhone(
          this.employee.id_person,
          this.newPhone
        )
          .then((response)=>{
            if(response.data.success){
              this.newPhone = [];
              this.getEmployee(this.employee.id_employee)
                .then(()=>{
                  this.$emit('showAlert', 'success', 'Celular cadastrado com sucesso.');
                });
            }
          })
          .catch((error)=>{
            console.log(error);
          })
          .finally(()=>{
            this.$emit('isLoading', false);
          });
      },

      updatePhone(index){
        this.$emit('isLoading', true);
        api.updatePhone(this.phones[index])
          .then((response)=>{
            if(response.data.success){
              this.editing.phones.editing = false;
              this.editing.phones.index   = null;
              this.getEmployee(this.employee.id_employee)
                .then(()=>{
                  this.$emit('showAlert', 'success', 'Celular atualizado com sucesso.');
                });
            }
          })
          .catch((error)=>{
            console.log(error);
            this.$emit('showAlert', 'error', `Ocorreu um erro no processo: ${error}`);
          })
          .finally(()=>{
            this.$emit('isLoading', false);
          });
      },

      deletePhone(index){
        this.$emit('isLoading', true);
        api.deletePhone(this.phones[index].id_phone)
          .then((response)=>{
            if(response.data.success){
              this.getEmployee(this.employee.id_employee)
                .then(()=>{
                  this.$emit('showAlert', 'success', 'Celular excluído com sucesso.');
                })
            }
          })
          .catch((error)=>{
            console.log(error);
            this.$emit('showAlert', 'error', `Ocorreu um erro no processo: ${error}`);
          })
          .finally(()=>{
            this.$emit('isLoading', false);
          });
      },

      closeDeleteFamilyDropdown(index){
        document.getElementById(`deleteFamily${index}`).click();
      },

      closeDeleteEmailDropdown(index){
        document.getElementById(`deleteEmail${index}`).click();
      },

      closeDeletePhoneDropdown(index){
        document.getElementById(`deletePhone${index}`).click();
      },

      replaceEmptyStringsWithNull(obj) {
        let newObj = JSON.parse(JSON.stringify(obj));
        function traverse(o) {
          for (let i in o) {
            if (o[i] !== null && typeof(o[i])=="object") {
              traverse(o[i]);
            } else if (o[i] === '') {
              o[i] = null;
            }
          }
        }
        traverse(newObj);
        return newObj;
      },

      updateEmployee(part, new_data){
        this.$emit('isLoading', true);
        api.updateEmployee(part, this.replaceEmptyStringsWithNull(new_data))
          .then((response)=>{
            if(response.data.success){
              switch(part){
                case 'personal':
                  this.editing.personal = false;
                  break;
                case 'address':
                  this.editing.address  = false;
                  break;
                case 'job':
                  this.editing.job      = false;
                  break;
              }
              this.getEmployee(this.employee.id_employee)
                .then(()=>{
                  this.$emit('showAlert', 'success', 'Funcionário atualizado com sucesso.');
                });
            }
          })
          .catch((error)=>{
            console.log(error);
            this.$emit('showAlert', 'error', `Ocorreu um erro no processo: ${error}`);
          })
          .finally(()=>{
            this.$emit('isLoading', false);
          });
      },

      addFamily(){
        this.newFamily.push({
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
        this.newFamily.splice(index, 1);
      },

      addPhone(){
        this.newPhone.push({
          id_contact_type: 1,
          phone:           null,
          obs:             null,
        });
      },

      removePhone(index){
        this.newPhone.splice(index, 1);
      },

      addEmail(){
        this.newEmail.push({
          id_contact_type: 2,
          email:           null,
          obs:             null,
        });
      },

      removeEmail(index){
        this.newEmail.splice(index, 1);
      },

      restoreBackup(type){
        switch(type){
          case 'personal':
            this.editing.personal = false;
            this.employee.name    = this.backup.employee.name;
            this.employee.surname = this.backup.employee.surname;
            this.employee.birth_d = this.backup.employee.birth_d;
            this.employee.birth_m = this.backup.employee.birth_m;
            this.employee.birth_y = this.backup.employee.birth_y;
            this.employee.cpf     = this.backup.employee.cpf;
            break;
          case 'address':
            this.editing.address = false;
            this.address         = JSON.parse(JSON.stringify(this.backup.address));
            break;
          case 'job':
            this.editing.job          = false;
            this.employee.id_job_type = this.backup.employee.id_job_type;
            break;
          case 'user':
            this.editing.user = false;
            this.user         = JSON.parse(JSON.stringify(this.backup.user));
            break;
          case 'phones':
            this.editing.phones = {
              editing: false,
              index:   null,
            };
            this.phones         = JSON.parse(JSON.stringify(this.backup.phones));
            break;
          case 'emails':
            this.editing.emails = {
              editing: false,
              index:   null,
            };
            this.emails         = JSON.parse(JSON.stringify(this.backup.emails));
            break;
          case 'family':
            this.editing.family = {
              editing: false,
              index:   null,
            };
            this.family         = JSON.parse(JSON.stringify(this.backup.family));
            break;
        }
      },

      resetProfile(){
        this.backup  = {};
        this.editing = {
          image:      false,
          personal:  false,
          address:   false,
          job:       false,
          user:      false,
          phones: {
            editing: false,
            index:   null,
          },
          emails: {
            editing: false,
            index:   null,
          },
          family: {
            editing: false,
            index:   null,
          }
        };
        this.imageSrc = '/img/user.png',
        this.employee = {
          id_entity:     null,
          id_person:     null,
          id_employee:   null,
          id_job_type:   null,
          job_type:      null,
          id_job_depart: null,
          job_depart:    null,
          salary:        0,
          name:          null,
          surname:       null,
          birth_d:       null,
          birth_m:       null,
          birth_y:       null,
          cpf:           null,
          active:        null,
        };
        this.address         = {};
        this.phones          = [];
        this.newPhone        = [];
        this.whatsapp_inputs = [];
        this.whatsapp_texts  = [];
        this.emails          = [];
        this.newEmail        = [];
        this.family          = [];
        this.newFamily       = [];
        this.user = {
          create:        false,
          id_user_group: null,
          user_group:    null,
          id_user:       null,
          user:          null,
          postfix:       null,
          pass:          null,
        };
        this.user_availability = {
          available: undefined,
          timer:     undefined,
        };
        this.user_pass = {
          old_pass: null,
          new_pass: null,
        };
      },
    },
  }
</script>