class UsersController < ApplicationController
  before_filter :get_user, :only => [:delete, :update, :edit, :show]

  # GET /users
  def index
    @users = User.all

    respond_to do |format|
      format.html # index.html.erb
      format.json { render json: @users }
    end
  end

  # GET /users/1
  def show
    respond_to do |format|
      format.html # show.html.erb
      format.json { render json: @user }
    end
  end

  # GET /users/new
  def new
    @user = User.new

    respond_to do |format|
      format.html # new.html.erb
      format.json { render json: @user }
    end
  end

  # GET /users/1/edit
  def edit
  end

  # POST /users
  def create
    @user = User.new(params[:user])

    respond_to do |format|
      if @user.save
        format.html { redirect_to @user, notice: 'User was successfully created.' }
        format.json { render json: @user, status: :created, location: @user }
      else
        format.html { render action: "new" }
        format.json { render json: @user.errors, status: :unprocessable_entity }
      end
    end
  end

  # PUT /users/1
  def update
    respond_to do |format|
      if @user.update_attributes(params[:user])
        format.html { redirect_to @user, notice: 'User was successfully updated.' }
        format.json { head :no_content }
      else
        format.html { render action: "edit" }
        format.json { render json: @user.errors, status: :unprocessable_entity }
      end
    end
  end

  # DELETE /users/1
  def destroy
    @user.destroy

    respond_to do |format|
      format.html { redirect_to users_url }
      format.json { head :no_content }
    end
  end
  
  # POST /login
  def login
    @user = User.where("lower(username) = ?", params[:username].downcase).first
    
    if @user != nil # Username exists
      if @user.password == params[:password]
        session[:user] = @user
      else
        flash[:error_login_password] = "error"
      end
    else # Username does not exist
      flash[:error_login_username] = "error"
    end
    
    redirect_to :back
  end
  
  # POST /logout
  def logout
    session[:user] = nil
    redirect_to :back
  end
  
  # DRY Functions
  def get_user
    @user = User.find(params[:id])
  end
end
